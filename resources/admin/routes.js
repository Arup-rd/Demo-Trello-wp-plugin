import Board from './Modules/Board';
import Task from './Modules/Task';
import Help from './Modules/Help';

export default [
    {
        path: '/board',
        name: 'Board',
        component: Board,
        children: [
            {
                path: 'task/:id',
                name: 'Task',
                component: Task
            }
        ]
    },
    {
        path: '/help',
        name: 'Help',
        component: Help
    }
];
