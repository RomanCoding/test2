<template>
    <div>
        <table class="table table-hover" v-if="this.departments">
            <thead>
            <tr>
                <th>Название</th>
                <th>Описание</th>
                <th>Действия</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="(department,index) in this.departments" @click="edit(department)" :key="department.id">
                <td v-text="department.name"></td>
                <td v-text="department.description"></td>
                <td>
                    <a href="#" @click="destroy(index, department.id)">
                        <span class="glyphicon glyphicon-erase">Удалить</span>
                    </a>
                </td>
            </tr>
            </tbody>
        </table>
        <new-department ref="new_department" id="new-department" @added="added"></new-department>
    </div>
</template>

<script>
    import NewDepartment from './NewDepartment.vue';
    export default {
        components: { NewDepartment },
        props: ['data'],
        data() {
            return {
                departments: [],
            }
        },
        watch: {
            data() {
                this.departments = this.data.departments;
            }
        },
        methods: {
            added(department) {
                this.$emit('added', department);
            },
            edit(department) {
                this.$refs.new_department.edit(department);
            },
            destroy(index, id) {
                axios.delete('/departments/' + id);
                this.departments.splice(index, 1);
            }
        }
    }
</script>