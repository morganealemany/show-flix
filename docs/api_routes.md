# Tableau des routes de l'API

| Routes | Nom de la route | Méthodes (HTTP) | Controller | methode()|
| :---------------: |:---------------:| :-----:| :-----:| :------: |
| /api/v1/tvshows | api_v1_tvshows_list | GET | App\Controller\Api\V1\TvShowController | list|
| /api/v1/tvshows | api_v1_tvshows_add | POST | App\Controller\Api\V1\TvShowController | add |
| /api/v1/tvshows/{id} | api_v1_tvshows_show | GET | App\Controller\Api\V1\TvShowController | show |
| /api/v1/tvshows{id} | api_v1_tvshows_edit | PUT/PATCH | App\Controller\Api\V1\TvShowController | edit |
| /api/v1/tvshows{id} | api_v1_tvshows_delete | DELETE | App\Controller\Api\V1\TvShowController | delete |
| /api/v1/categories | api_v1_categories | GET | App\Controller\Api\V1\CategoryController | list|
| /api/v1/categories | api_v1_categories_add | POST | App\Controller\Api\V1\CategoryController | add |
| /api/v1/categories/{id} | api_v1_categories_show | GET | App\Controller\Api\V1\CategoryController | show |
| /api/v1/categories{id} | api_v1_categories_edit | PUT/PATCH | App\Controller\Api\V1\CategoryController | edit |
| /api/v1/categories{id} | api_v1_categories_delete | DELETE | App\Controller\Api\V1\CategoryController | delete |
| /api/v1/characters | api_v1_characters | GET | App\Controller\Api\V1\CharacterController | list|
| /api/v1/characters | api_v1_characters_add | POST | App\Controller\Api\V1\CharacterController | add |
| /api/v1/characters/{id} | api_v1_characters_show | GET | App\Controller\Api\V1\CharacterController | show |
| /api/v1/characters{id} | api_v1_characters_edit | PUT/PATCH | App\Controller\Api\V1\CharacterController | edit |
| /api/v1/characters{id} | api_v1_characters_delete | DELETE | App\Controller\Api\V1\CharacterController | delete |