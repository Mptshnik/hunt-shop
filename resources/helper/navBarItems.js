import roles from "./roles";


const navBarItems = [

    {
        title: 'Главная',
        path: '/',
        roles: []
    },
    {
        title: 'Организация',
        path: '/organization',
        roles: [roles.ADMIN]
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
                path: '/item-categories',
                roles: [roles.ADMIN, roles.MARKETING_MANAGER]
            },
            {
                title: 'Акции',
                path: '/promotions',
                roles: [roles.ADMIN, roles.MARKETING_MANAGER]
            },
        ]
    },
    {
        title: 'Продажи',
        roles: [roles.ADMIN, roles.SALES_MANAGER],
        children: [
            {
                title: 'Клиенты',
                path: '/clients',
                roles: [roles.ADMIN, roles.SALES_MANAGER]
            },
            {
                title: 'Заказы',
                path: '/orders',
                roles: [roles.ADMIN, roles.SALES_MANAGER]
            },
            {
                title: 'Корзина',
                roles: [roles.ADMIN, roles.SALES_MANAGER],
                path: '/cart'
            },
        ]
    },
    {
        title: 'Закупки',
        roles: [roles.ADMIN, roles.PURCHASING_MANAGER],
        children: [
            {
                title: 'Заявки',
                path: '/applications',
                roles: [roles.ADMIN, roles.PURCHASING_MANAGER]
            },
            {
                title: 'Поставщики',
                path: '/providers',
                roles: [roles.ADMIN, roles.PURCHASING_MANAGER]
            }
        ]
    },
    {
        title: 'Документы',
        roles: [roles.ADMIN, roles.PURCHASING_MANAGER, roles.ACCOUNTANT],
        children: [
            {
                title: 'Товарные накладные',
                path: '/item-invoices',
                roles: [roles.ADMIN, roles.PURCHASING_MANAGER, roles.ACCOUNTANT]
            },
            {
                title: 'Счет-фактуры',
                path: '/invoices',
                roles: [roles.ADMIN, roles.PURCHASING_MANAGER, roles.ACCOUNTANT]
            }
        ]
    },
    {
        title: 'Статистика',
        roles: [roles.ADMIN, roles.ACCOUNTANT],
        children: [
            {
                title: 'Расходы',
                path: '/expenses-statistics',
                roles: [roles.ADMIN,  roles.ACCOUNTANT]
            },
            {
                title: 'Доходы',
                path: '/income-statistics',
                roles: [roles.ADMIN, roles.ACCOUNTANT]
            },
            {
                title: 'Продажи',
                path: '/sales-statistics',
                roles: [roles.ADMIN, roles.ACCOUNTANT, roles.SALES_MANAGER]
            },
            {
                title: 'Закупки',
                path: '/purchases-statistics',
                roles: [roles.ADMIN, roles.ACCOUNTANT, roles.PURCHASING_MANAGER]
            },
            {
                title: 'Сотрудники',
                path: '/employee-statistics',
                roles: [roles.ADMIN, roles.HR]
            },
        ]
    },

]

export default navBarItems
