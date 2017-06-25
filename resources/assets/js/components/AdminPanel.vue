<template>
    <div>
        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active">
                <a href="#departments" aria-controls="departments" role="tab"
                   data-toggle="tab">Отделы
                </a>
            </li>
            <li role="presentation">
                <a href="#workers" aria-controls="workers" role="tab"
                   data-toggle="tab">Сотрудники
                </a>
            </li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="departments">
                <departments :data="{departments: departments}" @added="addDepartment"></departments>
            </div>
            <div role="tabpanel" class="tab-pane" id="workers">
                <workers :data="{workers: this.workers, departments: this.departments}" @added="addWorker"></workers>
            </div>
        </div>
    </div>
</template>

<script>
    import Workers from './Workers.vue';
    import Departments from './Departments.vue';
    export default {
        components: {Workers, Departments},
        data() {
            return {
                workers: [],
                departments: [],
            }
        },
        created() {
            this.fetchWorkers();
            this.fetchDepartments();
        },
        methods: {
            fetchDepartments() {
                axios.get('/departments').then(({data}) => {
                    this.departments = data;
                });
            },
            fetchWorkers() {
                axios.get('/workers').then(({data}) => {
                    this.workers = data;
                });
            },
            addWorker(person) {
                this.workers.push(person);
                flash('Added!');
            },
            addDepartment(department) {
                this.departments.push(department);
                flash('Added!');
            }
        }
    }
</script>