<template>
    <div class="col-md-6" @edit="edit">
        <label for="name" class="col-md-4 control-label">Название</label>
        <div class="col-md-6">
            <input id="name" v-model="department['name']" type="text" class="form-control" required autofocus>
        </div>

        <div class="col-md-10">
            <textarea id="description" v-model="department['description']" class="form-control" cols="30" rows="10" placeholder="Описание (опционально)"></textarea>
        </div>

        <div class="col-md-6">
            <button type="submit" class="btn btn-default" @click="submit" v-text="buttonText">
            </button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['data'],
        data() {
            return {
                department: {
                    id: '',
                    name: '',
                    description: ''
                },
                editing: false,
            }
        },
        computed: {
            buttonText() {
                return this.editing ? 'Обновить' : 'Добавить';
            }
        },
        methods: {
            submit() {
                this.editing ? this.update() : this.add();
                this.editing = false;
                this.clearForm();
            },
            add() {
                axios.post('/departments', this.department).then((response) => {
                    this.$emit('added', response.data);
                });
            },
            update() {
                axios.patch('/departments/' + this.department.id, this.department).then(({data}) => {
                    if (data.success) {
                        flash('Updated!');
                    }
                });
            },
            clearForm() {
                this.department = {
                    id: '',
                    name: '',
                    description: ''
                };
            },
            edit(department) {
                this.editing = true;
                this.department = department;
            }
        }
    }
</script>