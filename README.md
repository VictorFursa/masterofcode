Install
------------------------------------------
1. Create database online_library (configure your db connect in  common/config/main-local.php )
2. run `composer install`
3. run `php init`
4  run `php yii migrate/up`
5. load fixtures `php yii fixture "*"`


Console commands:
------------------------------------------
1. `php yii book/remove-by-category-name categoryName`  - remove category and all books with this category name
2. `php yii book/remove-by-tag tagName`  - remove books by tag name
3. `php yii book/replace oldCategoryName newCategoryName` - this command will publish message in RebbitMQ queue to  сhanges the old category name in books to new one
4. `php yii book/consume-replace` - this command consume RebbitMQ сhanges the old category name in books to new one