<template>
    <div class="my_plugin_all_container" style="margin-top: 50px; margin-left: 50px;">
        <el-row style="color:black" >
            <el-col :span="6" v-for="(column,columnIndex) in data.columns" :key="columnIndex" class="column" >
                <div class="grid-content bg-purple"  @dragover.prevent @drop.prevent="moveTask($event, column.name)">
                    <h3 style=" color:white">{{column.name}}</h3>
                    <ul>
                        <div v-for="(item,taskIndex) in allTasks.allTask" :key="taskIndex">
                            <li draggable v-if="item.columnName === column.name" @dragstart="pickupTask($event,item.id)" :key="taskIndex" class="columnListItem">
                                <div>
                                    <h3>{{item.task}}</h3>
                                    <!-- <p>{{value}}</p> -->
                                    <el-button type="text" @click="showTask($event, item, columnIndex, taskIndex)" style="font-size:12px; color:teal">more</el-button>
                                    <el-dialog  :visible.sync="showTaskDetail">
                                        <input :value="Taskname" @change="updateTaskProperty($event, 'name', item.id)" @click="openUpdateBtn" class="editTask" />
                                        <textarea v-model="Taskdescription" @change="updateTaskProperty($event, 'description', item.id)" placeholder="+ Add description" @click="openUpdateBtn" class="editDescription"/>
                                        <span slot="footer" class="dialog-footer">
                                            <el-button type="primary" v-if="showUpdate" @click="updateTaskProperty">Update</el-button>
                                            <el-button type="danger"  @click="deleteTask">Delete Task</el-button>
                                        </span>
                                    </el-dialog>
                                </div>
                            </li>
                        </div>
                    </ul>
                    <input placeholder=" + Enter New Task" class="addTask" @keyup.enter="createTask($event, column.tasks, column.name)"/>
                </div>
                
            </el-col>
        </el-row>
        <!-- <div class="task-bg" v-if="isTaskOpen">
            <router-view/>
        </div> -->
    </div>
</template>

<script>
    export default {
        name: 'Board',
        data() {
            return {
                value: '',
                allTasks: [],
                showTaskDetail: false,
                showUpdate: false,
                Taskname: '',
                Taskdescription: '',
                TaskID: '',
                TaskIndex: Number, 
                columnIndex: Number,
                Task: []
            }
        },
        async created() {
            await this.$rest.get('getTask').then(res => {
                this.allTasks = res
            })
        },
        computed: {
            data() {
                return this.$store.state.data
            }
        },
        methods: {
            onSubmit() {
                this.$rest.post('task', { abc: this.value })
            },
            async fetchTask() {
                await this.$rest.get('getTask').then(res => {
                    this.allTasks = res
                })
            },
            openUpdateBtn() {
                this.showUpdate = true;
            },
            showTask(e, item, columnIndex, taskIndex) {
                this.showTaskDetail = true
                this.Taskname = item.task
                this.Taskdescription = item.description
                this.TaskID = item.id
                this.Task = item
                this.columnIndex = columnIndex
                this.TaskIndex = taskIndex
            },
            async createTask(e, tasks, columnName) {
                await this.$rest.post('task', { task: e.target.value, columnName: columnName }).then(res => {
                    console.log(res)
                })
                this.fetchTask();
                e.target.value = '';
            },
            async updateTaskProperty(e, key) {
                console.log(key, e.target.value, this.TaskID)
                await this.$rest.post('updateTask', { key: key, value: e.target.value, ID: this.TaskID }).then(res => {
                    console.log(res)
                })
                this.fetchTask();
                this.showTaskDetail = false
            },
            async deleteTask() {
                await this.$rest.post('deleteTask', { ID: this.TaskID }).then(res => {
                    console.log(res)
                })
                this.fetchTask();
                this.showTaskDetail = false
            },
            pickupTask(e, id) {
                this.TaskID = id
            },
            async moveTask(e, toTasks) {
                await this.$rest.post('moveTask', { toTask: toTasks, ID: this.TaskID }).then(res => {
                    console.log(res)
                })
                this.fetchTask();
            }
        }
    }

</script>

<style lang="scss">
@import '../../scss/board'
</style>
