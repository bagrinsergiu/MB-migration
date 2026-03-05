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

Optional for duplicate slugs:

`http://localhost/?mb_project_uuid=3020176d-edf2-4459-b0dc-1e5caecb0c5f&brz_project_id=4474615&mb_page_slug=prayer-request&mb_page_id=12229`<br>

`mb_page_id` - explicitly selects the page ID for the provided `mb_page_slug` (used only in single-page mode)

### Duplicate slug env configuration

Add these variables to your environment when a site has duplicate page slugs:

```dotenv
MB_PAGE_OVERRIDES={"5ee6bea4-5155-4f80-bbaf-171e02be966d":{"prayer-request":12229,"faq":456}}
MB_DUPLICATE_SLUG_STRATEGY=prefer_oldest
```

- `MB_PAGE_OVERRIDES`: explicit mapping `{ "site_uuid": { "slug": page_id } }`.
- `MB_DUPLICATE_SLUG_STRATEGY`: fallback strategy when override is absent (default: `prefer_oldest`; options: `prefer_visible`, `prefer_hidden`, `prefer_newest`, `prefer_first_position`).

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