# Devmart Webapp v3

This is the third version of the Devmart Webapp. It is a complete rewrite of the previous version, which was written in plain PHP.
This version uses a PHP framework called Laravel for the backend, and VueJS for the frontend.
The backend is a REST API, which is consumed by the frontend.

## Installation

1. Clone the repository.
2. Run `composer install` to backend install the dependencies.
3. Run `npm install` to install the frontend dependencies.
4. Run `cp .env.example .env` to copy the environment file and change the values to your own.
5. Run `php artisan key:generate` to generate an application key, which is required for Laravel to run. If you are using different environments, you may need to
   add the `--env` flag to the command.
6. Run `npm run dev` to compile the frontend assets.
7. Run `php artisan migrate` to run the database migrations. This will create the database tables.
8. Run `php artisan serve` to start the development server. This will start a PHP development server on port 8000. Additionally, you can change the port by
   adding the `--port=XXXX` flag to change the port. Or use the `--env=XXXX` flag to change the environment.
9. Additionally, you can run `npm run tailwind-watch`, which will watch for changes in the Tailwind CSS files and compile them automatically.

Here is a list of important directories and files:

- `/resources/js/components` - This is where the VueJS components are stored. You can find common, general components under `/Common`, and page layouts
  under `/Pages`.
- `/resources/js/router` - This is where all frontend routes are stored. The `index.js` file is the main file, which imports all the other routes.
  The `wiki-routes.js` file is where all the wiki routes are stored and is also used to generate the sidebar on the wiki pages.
- `/resources/js/models` - This is where all frontend models are stored. These models are used to interact with the backend API.
- `/resources/js/services` - This is where all frontend services and repositories are stored. Repositories are used to communicate with backend API endpoints.
- `/routes` - This is where all the backend routes are stored. The `web.php` file contains all the routes, including the API routes.
- `/app/Http/Controllers` - This is where all the backend controllers, that handle backend requests, are stored.
- `/app/Models` - This is where all backend models are stored. These models are used to interact with the database.
- `/storage/logs/laravel.log` - This is the log file for the application. You can use this to debug any issues. You can debug your backend code by using the
  Laravel `Log::debug()` or `Log::error()` functions.

## Add new Wiki pages

The wiki pages are stored in the `/resources/js/components/Pages/Wiki` directory. To add a new page, you need to create a new VueJS component in this directory (`.vue` file).
Place the components in the appropriate subdirectory, depending on the category of the page. For example: `/additionsplus`, `/skywarsreloaded`, etc.

The wiki pages are generated using the `wiki-routes.js` file. This file contains all the routes for the wiki pages. To add a new page, you need to add a new route
Please follow the instructions from the official VueJS router documentation, that can be found [here](https://router.vuejs.org/guide/essentials/nested-routes.html).
If you are placing the page in a subdirectory, you need to work with `nested routes`. See the example below:

```js
{
    // Create a new route under the /additionsplus path.
    path: '/additionsplus', 
    children: [
        {
            // Create a new route under the /additionsplus/setup-scoreboards path.
            // Will display as "Setup Scoreboards" in the sidebar.
            path: 'setup-scoreboards',
            // The 'name' property is required for the sidebar to link to the right route.
            name: 'ap-setup-scoreboards',
            // The component that will be rendered when the route is accessed.
            // This is not required for the item to show up in the sidebar, but it is required for the route to work.
            component: ImportedComponentHere,
        }, {
            path: 'actions',
            name: 'ap-actions',
            component: AnotherImportedComponentHere,
            meta: {
                // If you want to override the default generated name, you can use the meta property.
                // Which will display as "Action List" in the sidebar.
                name: 'Action List'
            }
        }
    ]
}
```
The example below should be placed in the `children` array of the `/wiki` route. It is also possible to add subroutes within your subroute.  
The sidebar will automatically generate the correct links for the subroutes and will by default use the `path` property to generate the name. 
This name is calculated by removing the "/" character and replacing all "-" characters with spaces. You can override this by using the `meta.name` property.

Subroutes also support home pages, eg: `/additionsplus` (no subroute). To add a home page, add a child route with `''` as path and give it a name. 
Please know that route names should be unique!
For example:
```js
{
    path: '/additionsplus',
    children: [
        {
            path: '',
            name: 'ap-home',
            component: ImportedComponentHere,
        }
    ]
}
```
