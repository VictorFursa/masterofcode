Install
------------------------------------------
1. Create database online_library (configure your db connect in  common/config/main-local.php )
2. run `composer install`
3. run `php init`
4  run `php yii migrate/up`
5. load fixtures `php yii fixture "*"`


Commands:
------------------------------------------
1. `php yii book/remove-by-category-name categoryName`  - remove category and all books with this category name
2. `php yii book/remove-by-tag tagName`  - remove books by tag name