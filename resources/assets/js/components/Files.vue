<template>
    <div>
        <div v-for="file in files" class="alert alert-info">
            {{ file.sender.name }} загрузил файл {{ moment(file.created_at).fromNow() }}:
            <a :href="link(file)">
                <span class="glyphicon glyphicon-download"></span>
            </a>
        </div>
        <div class="btn-group" v-if="order">
            <input ref="file_input" type="file" class="hidden" @change="onFileChange">
            <button class="btn btn-success" @click="open">Прикрепить файл</button>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    export default {
        props: ['order'],
        data() {
            return {
                files: [],
            }
        },
        methods: {
            link(file) {
                return file.path;
            },
            moment(date=[]) {
                return moment(date).locale('ru');
            },
            onFileChange (ele) {
                this.errors = {};

                let files = ele.target.files || ele.dataTransfer.files;

                if (!files.length) {
                    return;
                }

                this.file = files[0];

                var form = new FormData();
                form.append('file', this.file, this.file.name);
                axios.post('files/' + this.order.id, form).then((response) => {
                    this.files.push(response.data);
                });
            },
            open() {
                this.$refs.file_input.click()
            },
        },
        watch: {
            order() {
                this.files = this.order.files;
            }
        }
    }
</script>