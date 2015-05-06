DIR=$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )
$DIR/vendor/phpunit/phpunit/phpunit --bootstrap $DIR/bootstrap.php $DIR/tests/
