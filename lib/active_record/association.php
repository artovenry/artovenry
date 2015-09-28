<?
namespace Art\ActiveRecord;

trait Association{
        /**
         * Internal method to construct the queries for both the has_one and
         * has_many methods. These two types of association are identical; the
         * only difference is whether find_one or find_many is used to complete
         * the method chain.
         *
         * @param  string      $associated_class_name
         * @param  null|string $foreign_key_name
         * @param  null|string $foreign_key_name_in_current_models_table
         * @param  null|string $connection_name
         * @return ORMWrapper
         */
        protected function _has_one_or_many($associated_class_name, $foreign_key_name=null, $foreign_key_name_in_current_models_table=null, $connection_name=null) {
            $base_table_name = self::_get_table_name(get_class($this));
            $foreign_key_name = self::_build_foreign_key_name($foreign_key_name, $base_table_name);
            
            $where_value = ''; //Value of foreign_table.{$foreign_key_name} we're 
                               //looking for. Where foreign_table is the actual 
                               //database table in the associated model.
            
            if(is_null($foreign_key_name_in_current_models_table)) {
                //Match foreign_table.{$foreign_key_name} with the value of 
                //{$this->_table}.{$this->id()}
                $where_value = $this->id(); 
            } else {
                //Match foreign_table.{$foreign_key_name} with the value of 
                //{$this->_table}.{$foreign_key_name_in_current_models_table}
                $where_value = $this->$foreign_key_name_in_current_models_table;
            }
            
            return self::factory($associated_class_name, $connection_name)->where($foreign_key_name, $where_value);
        }

        /**
         * Helper method to manage one-to-one relations where the foreign
         * key is on the associated table.
         *
         * @param  string      $associated_class_name
         * @param  null|string $foreign_key_name
         * @param  null|string $foreign_key_name_in_current_models_table
         * @param  null|string $connection_name
         * @return ORMWrapper
         */
        protected function has_one($associated_class_name, $foreign_key_name=null, $foreign_key_name_in_current_models_table=null, $connection_name=null) {
            return $this->_has_one_or_many($associated_class_name, $foreign_key_name, $foreign_key_name_in_current_models_table, $connection_name);
        }

        /**
         * Helper method to manage one-to-many relations where the foreign
         * key is on the associated table.
         *
         * @param  string      $associated_class_name
         * @param  null|string $foreign_key_name
         * @param  null|string $foreign_key_name_in_current_models_table
         * @param  null|string $connection_name
         * @return ORMWrapper
         */
        protected function has_many($associated_class_name, $foreign_key_name=null, $foreign_key_name_in_current_models_table=null, $connection_name=null) {
            return $this->_has_one_or_many($associated_class_name, $foreign_key_name, $foreign_key_name_in_current_models_table, $connection_name);
        }

        /**
         * Helper method to manage one-to-one and one-to-many relations where
         * the foreign key is on the base table.
         *
         * @param  string      $associated_class_name
         * @param  null|string $foreign_key_name
         * @param  null|string $foreign_key_name_in_associated_models_table
         * @param  null|string $connection_name
         * @return $this|null
         */
        protected function belongs_to($associated_class_name, $foreign_key_name=null, $foreign_key_name_in_associated_models_table=null, $connection_name=null) {
            $associated_table_name = self::_get_table_name(self::$auto_prefix_models . $associated_class_name);
            $foreign_key_name = self::_build_foreign_key_name($foreign_key_name, $associated_table_name);
            $associated_object_id = $this->$foreign_key_name;
            
            $desired_record = null;
            
            if( is_null($foreign_key_name_in_associated_models_table) ) {
                //"{$associated_table_name}.primary_key = {$associated_object_id}"
                //NOTE: primary_key is a placeholder for the actual primary key column's name
                //in $associated_table_name
                $desired_record = self::factory($associated_class_name, $connection_name)->where_id_is($associated_object_id);
            } else {
                //"{$associated_table_name}.{$foreign_key_name_in_associated_models_table} = {$associated_object_id}"
                $desired_record = self::factory($associated_class_name, $connection_name)->where($foreign_key_name_in_associated_models_table, $associated_object_id);
            }
            
            return $desired_record;
        }

        /**
         * Helper method to manage many-to-many relationships via an intermediate model. See
         * README for a full explanation of the parameters.
         *
         * @param  string      $associated_class_name
         * @param  null|string $join_class_name
         * @param  null|string $key_to_base_table
         * @param  null|string $key_to_associated_table
         * @param  null|string $key_in_base_table
         * @param  null|string $key_in_associated_table
         * @param  null|string $connection_name
         * @return ORMWrapper
         */
        protected function has_many_through($associated_class_name, $join_class_name=null, $key_to_base_table=null, $key_to_associated_table=null,  $key_in_base_table=null, $key_in_associated_table=null, $connection_name=null) {
            $base_class_name = get_class($this);

            // The class name of the join model, if not supplied, is
            // formed by concatenating the names of the base class
            // and the associated class, in alphabetical order.
            if (is_null($join_class_name)) {
                $model = explode('\\', $base_class_name);
                $model_name = end($model);
                if (substr($model_name, 0, strlen(self::$auto_prefix_models)) == self::$auto_prefix_models) {
                    $model_name = substr($model_name, strlen(self::$auto_prefix_models), strlen($model_name));
                }
                $class_names = array($model_name, $associated_class_name);
                sort($class_names, SORT_STRING);
                $join_class_name = join("", $class_names);
            }

            // Get table names for each class
            $base_table_name = self::_get_table_name($base_class_name);
            $associated_table_name = self::_get_table_name(self::$auto_prefix_models . $associated_class_name);
            $join_table_name = self::_get_table_name(self::$auto_prefix_models . $join_class_name);

            // Get ID column names
            $base_table_id_column = (is_null($key_in_base_table)) ?
                self::_get_id_column_name($base_class_name) :
                $key_in_base_table;
            $associated_table_id_column = (is_null($key_in_associated_table)) ?
                self::_get_id_column_name(self::$auto_prefix_models . $associated_class_name) :
                $key_in_associated_table;

            // Get the column names for each side of the join table
            $key_to_base_table = self::_build_foreign_key_name($key_to_base_table, $base_table_name);
            $key_to_associated_table = self::_build_foreign_key_name($key_to_associated_table, $associated_table_name);
    
            /*
                "   SELECT {$associated_table_name}.*
                      FROM {$associated_table_name} JOIN {$join_table_name}
                        ON {$associated_table_name}.{$associated_table_id_column} = {$join_table_name}.{$key_to_associated_table}
                     WHERE {$join_table_name}.{$key_to_base_table} = {$this->$base_table_id_column} ;"
            */

            return self::factory($associated_class_name, $connection_name)
                ->select("{$associated_table_name}.*")
                ->join($join_table_name, array("{$associated_table_name}.{$associated_table_id_column}", '=', "{$join_table_name}.{$key_to_associated_table}"))
                ->where("{$join_table_name}.{$key_to_base_table}", $this->$base_table_id_column); ;
        }
}
