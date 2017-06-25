<template>
    <div>
        <table class="table table-hover" v-if="this.workers">
            <thead>
            <tr>
                <th>Фамилия</th>
                <th>Имя</th>
                <th>Отчество</th>
                <th>Отдел</th>
                <th>Описание</th>
                <th>E-Mail</th>
                <th>Удалить сотрудника</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(worker, index) in this.workers" @click="edit(worker)" :key="worker.id">
                <td v-text="worker.surname"></td>
                <td v-text="worker.name"></td>
                <td v-text="worker.patronymic"></td>
                <td v-text="worker.department.name"></td>
                <td v-text="worker.description"></td>
                <td v-text="worker.email"></td>
                <td>
                    <a href="#" @click="destroy(index, worker.id)">
                        <span class="glyphicon glyphicon-erase">Удалить</span>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
        <new-worker ref="new_worker" @added="added"></new-worker>
    </div>
</template>

<script>
    import NewWorker from './NewWorker.vue';
    export default {
        components: {NewWorker},
        props: ['data'],
        data() {
            return {
                workers: [],
                departments: []
            }
        },
        watch: {
            data() {
                this.workers = this.data.workers;
                this.departments = this.data.departments;
            }
        },
        methods: {
            added(person) {
                this.$emit('added', person);
            },
            edit(worker) {
                this.$refs.new_worker.edit(worker);
            },
            destroy(index, id) {
                axios.delete('/workers/' + id);
                this.workers.splice(index, 1);
            }
        }
    }
</script>