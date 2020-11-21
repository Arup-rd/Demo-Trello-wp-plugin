import { v4 as uuidv4 } from 'uuid';
import Vuex from 'vuex'
import Data from './data'

window.MyPlugin.Vue.use(Vuex)

export const store = new Vuex.Store({
    state: {
        data: Data
    },
    getters: {
        getTask (state) {
            return (ID) => {
                console.log('id', ID)
                console.log(state)
                for (const column of state.data.columns) {
                    for (const item of column.tasks) {
                        console.log('taskId', item.id)
                        if (item.id === ID) {
                            return item
                        }
                    }
                }
            }
        }
    },
    mutations: {
        CREATE_TASK (state, { tasks, name }) {
            tasks.push({
                name,
                id: uuidv4(),
                description: ''
            })
        },
        MOVE_TASK (state, { fromTask, toTasks, taskIndex }) {
            const tasktoMove = fromTask.splice(taskIndex, 1)[0]
            toTasks.push(tasktoMove)
        },
        UPDATE_TASK(state, { key, tasks, value }) {
            tasks[key] = value   
        },
        DELETE_TASK(state, { taskIndex, columnIndex }) {
            return state.data.columns[columnIndex].tasks.splice(taskIndex, 1)[0]
        }
    }
})
