<?php
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'mvc');
define('DB_TABLE_MIGRATIONS', 'migrations');

echo PHP_EOL;
$migration = new Migration();
$files = $migration->getMigrationFiles();
if (empty($files)) {
    echo 'База данных в актуальном состоянии.'.PHP_EOL;
} else {
    echo 'Начинаем миграцию...'.PHP_EOL;

    // Накатываем миграцию для каждого файла
    foreach ($files as $file) {
        $migration->migration($file);
        // Выводим название выполненного файла
        echo basename($file).PHP_EOL;
    }

    echo 'Миграция завершена.'.PHP_EOL;
}

/**
 * Данный скрипт позволяет осуществить выполнение миграций
 * SQL файлы с миграциями хранятся в каталоге migrations
 * В базе данных в таблице migrations хранятся уже выполненные миграции
 * Имена SQL файлов состоят из двух частей первая часть порядковый номер
 * далее знак подчеркивание и наименование которое отражает содержимое файла
 * например: 0001_create_users.php
 */
class Migration
{
    private $conn;
    /**
     * В конструкторе класса осуществляется подключение к базе данных
     * @throws Exception
     */
    public function __construct()
    {
        $errorMessage = 'Невозможно подключиться к серверу базы данных'.PHP_EOL;
        $this->conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        if (!$this->conn)
            throw new Exception($errorMessage);
        else {
            $query = $this->conn->query('set names utf8');
            if (!$query)
                throw new Exception($errorMessage);
            else
                return $this->conn;
        }
    }

    /**
     * Метод getMigrationFiles() получение массива еще не обработынных файлов
     * @return array/false Массив с именами новых (не исполняемых ранее) SQL файлов, false если нет новых миграций
     */
    public function getMigrationFiles()
    {

        $sqlFolder = str_replace('\\', '/', realpath(dirname(__FILE__)) . '/migrations/');
        $allFiles = glob($sqlFolder . '*.sql');
        $query = sprintf('show tables from `%s` like "%s"', DB_NAME, DB_TABLE_MIGRATIONS);
        $data = $this->conn->query($query);
        $firstMigration = !$data->num_rows;
        if ($firstMigration) {
            return $allFiles;
        }
        $versionsFiles = array();
        $query = sprintf('select `name` from `%s`', DB_TABLE_MIGRATIONS);
        $data = $this->conn->query($query)->fetch_all(MYSQLI_ASSOC);
        foreach ($data as $row) {
            array_push($versionsFiles, $sqlFolder . $row['name']);
        }
        return array_diff($allFiles, $versionsFiles);
    }


    public function migration($file)
    {
        // Формируем команду выполнения mysql-запроса из внешнего файла
        $command = sprintf('mysql -u%s -p%s -h %s -D %s < %s', DB_USER, DB_PASSWORD, DB_HOST, DB_NAME, $file);
        // Выполняем shell-скрипт
        shell_exec($command);
        // Вытаскиваем имя файла, отбросив путь
        $baseName = basename($file);
        // Добавляем миграции в таблицу migrations
        $query = sprintf('insert into `%s` (`name`) values("%s")', DB_TABLE_MIGRATIONS, $baseName);
        $this->conn->query($query);
    }
}