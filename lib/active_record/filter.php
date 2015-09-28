<?
namespace Art\ActiveRecord;

trait Filter{
    /**
     * Add a custom filter to the method chain specified on the
     * model class. This allows custom queries to be added
     * to models. The filter should take an instance of the
     * ORM wrapper as its first argument and return an instance
     * of the ORM wrapper. Any arguments passed to this method
     * after the name of the filter will be passed to the called
     * filter function as arguments after the ORM class.
     *
     * @return ORMWrapper
     */
    public function filter() {
        $args = func_get_args();
        $filter_function = array_shift($args);
        array_unshift($args, $this);
        if (method_exists($this->_class_name, $filter_function)) {
            return call_user_func_array(array($this->_class_name, $filter_function), $args);
        }
    }
  
}
