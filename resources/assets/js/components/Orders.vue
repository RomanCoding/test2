<template>
    <div>
        <!-- Nav tabs -->
        <ul class="nav nav-tabs" role="tablist" style="margin-bottom: 1em;">
            <li role="presentation" class="active">
                <a href="#incoming" aria-controls="incoming" role="tab"
                   data-toggle="tab">Входящие</a>
            </li>
            <li role="presentation">
                <a href="#outcoming" aria-controls="outcoming" role="tab"
                   data-toggle="tab">Исходящие</a>
            </li>
            <li role="presentation">
                <a href="#archived" aria-controls="archived" role="tab"
                   data-toggle="tab">Архив</a>
            </li>
        </ul>
        <div class="pre-scrollable">
            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="incoming">
                    <!-- Tab panes -->
                    <div class="col-md-3 no-left-padding">
                        <div class="list-group">
                            <a href="#" :class="classes(order)" v-for="order in incomingOrders"
                               @click="loadOrder(order)">
                                <span class="badge" v-if="order.unreadCount" v-text="order.unreadCount"></span>
                                <h4 class="list-group-item-heading">{{ order.title }}</h4>
                                <p class="list-group-item-text">
                                    Поручил: {{ order.creator.name }}
                                </p>
                                <p class="list-group-item-text">
                                    Срок сдачи: {{ order.due_date }}
                                </p>
                                <span v-if="order.important" class="glyphicon glyphicon-exclamation-sign">Важно!</span>
                            </a>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="outcoming">
                    <!-- Tab panes -->
                    <div class="col-md-3" style="padding-left: 0; padding-right: 0;">
                        <div class="list-group">
                            <a href="#" class="list-group-item" v-for="order in outgoingOrders"
                               @click="currentOrder = order">
                                <h4 class="list-group-item-heading">{{ order.title }}</h4>
                                <p class="list-group-item-text">
                                    Исполнитель: {{ order.worker.name }}
                                </p>
                                <p class="list-group-item-text">
                                    Срок сдачи: {{ order.due_date }}
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
                <div role="tabpanel" class="tab-pane" id="archived">
                    <!-- Tab panes -->
                    <div class="col-md-3" style="padding-left: 0; padding-right: 0;">
                        <div class="row">
                            <div class="form-group">
                                <label for="searchText" class="col-xs-6">По заголовку</label>
                                <div class="col-xs-6">
                                    <input id="searchText" type="text" v-model="searchText"
                                           class="form-control input-sm" @keyup.enter="filter">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="searchDateStart" class="col-xs-6">Дата от</label>
                                <div class="col-xs-6">
                                    <input id="searchDateStart" type="date" v-model="searchDateStart"
                                           class="form-control input-sm">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="searchDateDue" class="col-xs-6">Дата до</label>
                                <div class="col-xs-6">
                                    <input id="searchDateDue" type="date" v-model="searchDateDue"
                                           class="form-control input-sm">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <button @click="filter" class="btn btn-success">Поиск</button>
                        </div>

                        <div class="list-group">
                            <a href="#" class="list-group-item" v-for="order in archOrders"
                               @click="currentOrder = order">
                                <h4 class="list-group-item-heading">{{ order.title }}</h4>
                                <p class="list-group-item-text">
                                    Исполнитель: {{ order.worker.name }}
                                </p>
                                <p class="list-group-item-text">
                                    Срок сдачи: {{ order.due_date }}
                                </p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6" style="padding-left: 0;">
                <messages v-bind:order="currentOrder"></messages>
            </div>
            <div class="col-md-3" style="padding-left: 0;">
                <files v-bind:order="currentOrder"></files>
            </div>
        </div>
        <div class="col-md-12 new-order no-left-padding">
            <new-order :workers="this.workers" @added="add">

            </new-order>
        </div>
    </div>
</template>

<script>
    import moment from 'moment';
    import NewOrder from './NewOrder.vue';
    import Messages from './Messages.vue';
    import Files from './Files.vue';
    export default {
        components: {NewOrder, Messages, Files},
        props: ['workers'],

        data() {
            return {
                incomingOrders: [],
                outgoingOrders: [],
                archivedOrders: [],
                archOrders: [],
                currentOrder: null,
                searchText: '',
                searchDateStart: null,
                searchDateDue: null,
            }
        },
        created() {
            axios.get('/orders/').then(({data}) => {
                this.incomingOrders = data.incomingOrders;
                this.outgoingOrders = data.outgoingOrders;
                this.archivedOrders = data.archivedOrders;
                this.archOrders = data.archivedOrders;
            });

        },
        methods: {
            add(order) {
                this.outgoingOrders.push(order);
            },
            classes(order) {
                return [
                    'list-group-item',
                    moment(order.due_date).diff(moment(), 'days') < 4 ? 'list-group-item-warning' : '',
                    (moment(order.due_date).diff(moment(), 'days') < 2) ? 'list-group-item-danger' : '',
                ];
            },
            moment(date = []) {
                return moment([]).locale('ru');
            },
            loadOrder(order) {
                this.currentOrder = order;
                if (order.unreadCount) {
                    axios.post('/orders/' + order.id + '/read').then(() => {
                        order.unreadCount = 0;
                    });
                }
            },
            filter() {
                console.log('213');
                this.archOrders = this.archivedOrders;
                if (this.searchText)
                    this.archOrders = this.archOrders.filter(order => order.title.indexOf(this.searchText) >= 0);
                if (this.searchDateStart)
                    this.archOrders = this.archOrders.filter(order => moment(order.due_date).isSameOrAfter(moment(this.searchDateStart)));
                if (this.searchDateDue)
                    this.archOrders = this.archOrders.filter(order => moment(order.due_date).isSameOrBefore(moment(this.searchDateDue)));
            }
        }
    }
</script>

<style scoped>
    .pre-scrollable {
        max-height: 500px;
    }
</style>