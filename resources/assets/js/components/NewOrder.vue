<template>
    <div>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">Новое поручение</h3>
            </div>
            <div class="panel-body">
                <div class="form-group">
                    <input type="text" v-model="title" placeholder="Заголовок" class="form-control">
                </div>
                <div class="form-group">
                    <textarea v-model="body" rows="5" class="form-control" placeholder="Задание"></textarea>
                </div>
                <div class="form-group col-xs-6 no-left-padding">
                    <select v-model="worker_id" class="form-control">
                        <option v-for="worker in workers" :value="worker.id" v-text="fullname(worker)"></option>
                    </select>
                </div>
                <div class="form-group form-inline col-xs-4">
                    <label for="date">Выполнить до:</label>
                    <input id="date" v-model="due_date" type="date" class="form-control">
                </div>
                <div class="form-group col-xs-2">
                    <input type="checkbox" id="checkbox-success" v-model="important" autocomplete="off">
                    <div class="btn-group">
                        <label for="checkbox-success" class="btn btn-success">
                            <span class="glyphicon glyphicon-ok"></span>
                            <span> </span>
                        </label>
                        <label for="checkbox-success" class="btn btn-default active">
                            Важно
                        </label>
                    </div>
                </div>
                <div class="btn-group">
                    <input ref="file_input" type="file" class="hidden" @change="onFileChange">
                    <button class="btn btn-success" @click="open">Прикрепить файл</button>
                    <button class="btn btn-default col-xs-push-1" @click="create">Отправить</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['workers'],

        data() {
            return {
                title: '',
                body: '',
                worker_id: '',
                due_date: '',
                important: 0,
                formData: null
            }
        },

        methods: {
            fullname(worker) {
                return worker.surname + ' ' + worker.name + ' ' + worker.patronymic;
            },
            open() {
                this.$refs.file_input.click()
            },
            clear() {
                this.title = '';
                this.body = '';
                this.worker_id = '';
                this.due_date = '';
                this.important = 0;
                this.formData = null;
            },
            create() {
                if (!this.formData) {
                    this.formData = new FormData();
                }
                this.formData.append('title', this.title);
                this.formData.append('body', this.body);
                this.formData.append('worker_id', this.worker_id);
                this.formData.append('due_date', this.due_date);
                this.formData.append('important', +this.important);
                axios.post('/orders', this.formData).then(({data}) => {
                    this.$emit('added', data);
                    flash('Заказ отправлен!');
                });
                this.clear();
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
                this.formData = form;
            },
        }
    }
</script>

<style scoped>
    .form-group input[type="checkbox"] {
        display: none;
    }

    .form-group input[type="checkbox"] + .btn-group > label span {
        width: 20px;
    }

    .form-group input[type="checkbox"] + .btn-group > label span:first-child {
        display: none;
    }

    .form-group input[type="checkbox"] + .btn-group > label span:last-child {
        display: inline-block;
    }

    .form-group input[type="checkbox"]:checked + .btn-group > label span:first-child {
        display: inline-block;
    }

    .form-group input[type="checkbox"]:checked + .btn-group > label span:last-child {
        display: none;
    }
</style>