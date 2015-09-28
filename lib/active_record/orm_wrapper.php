<?
namespace Art\ActiveRecord;
require __DIR__ . "/initializer.php";
require __DIR__ . "/finder.php";
require __DIR__ . "/filter.php";

class ORMWrapper extends \ORM {
  use Initializer;
  use Finder;
  use Filter;

    /**
     * The wrapped find_one and find_many classes will
     * return an instance or instances of this class.
     *
     * @var string $_class_name
     */
    protected $_class_name;

    /**
     * Set the name of the class which the wrapped
     * methods should return instances of.
     *
     * @param  string $class_name
     * @return void
     */
    public function set_class_name($class_name) {
        $this->_class_name = $class_name;
    }

    /**
     * Factory method, return an instance of this
     * class bound to the supplied table name.
     *
     * A repeat of content in parent::for_table, so that
     * created class is ORMWrapper, not ORM
     *
     * @param  string $table_name
     * @param  string $connection_name
     * @return ORMWrapper
     */
    public static function for_table($table_name, $connection_name = parent::DEFAULT_CONNECTION) {
        self::_setup_db($connection_name);
        return new static($table_name, array(), $connection_name);
    }


        /**
         * Wrap Idiorm's create method to return an
         * empty instance of the class associated with
         * this wrapper instead of the raw ORM class.
         *
         *  return ORMWrapper|bool
         */
        public function create($data=null) {
            return $this->_create_model_instance(parent::create($data));
        }
  
        /**
         * Method to create an instance of the model class
         * associated with this wrapper and populate
         * it with the supplied Idiorm instance.
         *
         * @param  ORM $orm
         * @return bool|Model
         */
        protected function _create_model_instance($orm) {
            if ($orm === false) {
                return false;
            }
            $model = new $this->_class_name();
            $model->set_orm($orm);
            return $model;
        }

}
