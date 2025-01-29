## Ministry Brands Migration

### Run the following

1. `git clone https://github.com/bagrinsergiu/MB-migration.git`
2. go to the project directory
3. set up options to your environment `.env.dev` for development and `.env.prod` for the production. <br>
Addition:  in `docker-compose.yaml` configurations, use the value for `APP_ENV`<br>
`prod` - for production<br>
`dev` - for development<br>

4. execute the command to build the project `docker compose build migration` && `docker compose up -d`

Once started, you should be able to access `http://localhost/`.
you should get:

```
HTTP/1.1 400 Bad Request

{
"error": "Invalid mb_project_uuid"
}
```

Great, so the project is ready to go.

### Sample request

`http://localhost/?mb_project_uuid=3020176d-edf2-4459-b0dc-1e5caecb0c5f&brz_project_id=4474615`

`mb_project_uuid` - specify the original uuid of the Ministry Brands project<br>
`brz_project_id` - specify the target id of the Brizy project


for development there is an additional parameter that allows you to build only one page of the project<br>

`http://localhost/?mb_project_uuid=3020176d-edf2-4459-b0dc-1e5caecb0c5f&brz_project_id=4474615&mb_page_slug=home`<br>

`mb_page_slug` - point to the slug of the page you want to build

### Response 

you should get a response when the migration completes successfully<br>
example response:
```
{
  "status": "success",
  "UMID": "cbcd98f2edcdd92fa7e0282feb8fa9c2",
  "progress": {
    "Total": 15,
    "Success": 14
  },
  "processTime": 128
}
```