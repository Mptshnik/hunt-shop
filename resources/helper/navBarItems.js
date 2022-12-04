import roles from "./roles";


const navBarItems = [

    {
        title: 'Главная',
        path: '/',
        roles: []
    },
    {
        title: 'Кадры',
        roles: [roles.ADMIN, roles.HR],
        children:[
            {
                title: 'Должности',
                path: '/posts',
                roles: [roles.ADMIN, roles.HR]
            },
            {
                title: 'Сотрудники',
                path: '/employees',
                roles: [roles.ADMIN, roles.HR]
            },
            {
                title: 'Пользователи',
                path: '/users',
                roles: [roles.ADMIN]
            },
        ]
    },
    {
        title: 'Товары',
        roles: [roles.ADMIN, roles.PURCHASING_MANAGER, roles.MARKETING_MANAGER],
        children: [
            {
                title: 'Товары',
                path: '/items',
                roles: [roles.ADMIN, roles.PURCHASING_MANAGER]
            },
            {
                title: 'Категории',
                path: '/categories',
                roles: [roles.ADMIN, roles.MARKETING_MANAGER]
            },
        ]
    }
]

export default navBarItems
