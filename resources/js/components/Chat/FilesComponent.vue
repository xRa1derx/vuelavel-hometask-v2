<template>
    <div class="file" ref="fileRef">
        <div class="files-actions" @click.self="fileContextMenu($event)">
            <font-awesome-icon
                :icon="['fas', 'download']"
                @click="downloadFile(file)"
            />
            <p
                class="file-name"
                @click.self="fileContextMenu($event)"
                v-html="subString"
            ></p>

            <font-awesome-icon
                v-if="$store.state.auth.user.id === message.sender.id"
                class="delete-file"
                :icon="['fas', 'trash']"
                data-toggle="modal"
                data-target="#deleteModal"
                @click="deleteFile(file)"
            />
        </div>
    </div>
</template>

<script>
import axios from "axios";
import { computed, ref } from "vue";
export default {
    props: ["file", "message"],
    emits: ["deleteFile", "fileContextMenu"],
    setup(props, { emit }) {
        const fileRef = ref(null);
        const downloadFile = (file) => {
            axios({
                url: `/api/file/${file.id}`,
                method: "GET",
                responseType: "blob", // important
            }).then((response) => {
                // Service that handles ajax call
                const url = window.URL.createObjectURL(
                    new Blob([response.data])
                );
                const link = document.createElement("a");
                link.href = url;
                link.setAttribute("download", file.name);
                document.body.appendChild(link);
                link.click();
                link.remove();
            });
        };
        const fileContextMenu = (event) => {
            console.log(event.target);
            emit("fileContextMenu", event, props.message, "file", fileRef);
        };
        const deleteFile = (file) => {
            const res = {
                id: [file.id],
                name: file.name,
            };
            emit("deleteFile", res);
        };
        const subString = computed(() => {
            const ext = props.file.name.split(".").reverse()[0];
            if (ext === "pdf") {
                if (props.file.name && props.file.name.length > 20) {
                    return `<a href='../../${
                        props.file.path
                    }' target='_blank'>${
                        props.file.name.slice(0, 16) + "..." + ext
                    }</a>`;
                }
                return `<a href='../../${props.file.path}' target='_blank'>${props.file.name}</a>`;
            } else {
                if (props.file.name && props.file.name.length > 20) {
                    return props.file.name.slice(0, 16) + "..." + ext;
                }
                return props.file.name;
            }
        });
        return {
            downloadFile,
            deleteFile,
            subString,
            fileContextMenu,
            fileRef,
        };
    },
};
</script>

<style scoped>
.files-actions {
    display: flex;
    padding: 10px 5px;
    gap: 7px;
    cursor: pointer;
}

.files-actions > svg {
    cursor: pointer;
    align-self: center;
    border-radius: 2px;
    padding: 3px 5px;
}

.files-actions > svg:hover {
    background-color: #646464;
}

.delete-file {
    margin-left: auto;
}

.file-name {
    margin: 0;
}

:deep(a) {
    color: #fff;
    text-decoration: underline;
}

:deep(a:hover) {
    color: #ffb96f;
}
</style>
