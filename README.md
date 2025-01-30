Translation Service
Loom Video Demo
A detailed demo of API results can be viewed via the following Loom video link:

https://www.loom.com/share/4544d726ca62426ab8ae8b73ae65e71f?sid=bbe162f9-db5c-4bc0-aec0-57865c0de0ed

Description
A robust and scalable API for managing translations, handling language localization, and associating translations with tags. This project allows for seamless creation, retrieval, 
and management of translations, optimized for performance with bulk insertions, caching, and chunking methods.

Features
Translation Management: Create, read, update, and delete translations.
Tagging System: Tag translations with multiple labels and categories.
Searchable Translations: Search translations by key, content, and tags.
Bulk Operations: Efficient handling of large datasets with bulk insertions and optimized queries.
Caching: Optimized for performance with caching and bulk data retrieval.
Installation
To set up and run the application on your local environment, follow these steps:

1. Clone the repository
Use the following command to clone the repository to your local machine:

git clone https://github.com/nomannafees/translation_service

2. Install Composer dependencies
Navigate into the project directory and install the required PHP dependencies using Composer cd translation_service composer install

3. Set up the environment file
Must Copy the .env.example file to .env
4. Set up the database
If you don't already have a database set up, create one and configure it in your .env file:

DB_CONNECTION=mysql
 DB_HOST=127.0.0.1
 DB_PORT=3306
 DB_DATABASE=translation_service
 DB_USERNAME=root
 DB_PASSWORD=

5. Generate the application key
Run the following command to generate the application key: php artisan key:generate

6. Run database migrations
Set up the database by running migrations to create the necessary tables: php artisan migrate

Also run the seeders php artisan db:seed --class=TranslationSeeder

API Documentation
Endpoints
1. Create a Translation
POST /api/translations

json
Copy
Edit
{
    "locale": "en",
    "key": "home_welcome",
    "content": "Welcome to our website!",
    "tags": [1, 2]
}
2. Get All Translations
GET /api/translations

Retrieve a paginated list of translations with their associated tags.

3. Get a Specific Translation
GET /api/translations/{id}

Retrieve details of a specific translation by ID.

4. Update a Translation
PUT /api/translations/{id}

json
Copy
Edit
{
    "locale": "en",
    "key": "home_welcome",
    "content": "Welcome to our updated website!",
    "tags": [1, 3]
}
5. Delete a Translation
DELETE /api/translations/{id}

6. Search Translations
GET /api/translations/search?tags=tag1&key=home_welcome

Search translations by tags, keys, or content.

7. Export Translations
GET /api/translations/export

Export all translations as JSON. This is optimized with caching and bulk querying.

Seeder & Performance Optimization
The seeder script allows you to insert large datasets (e.g., 100k records) efficiently using bulk inserts.
It uses chunking to handle large datasets and caching for faster data retrieval in export operations.