<?php
    abstract Class Enumeration {
        private static $constantCacheArray = NULL;
        
        private static function getConstants(){
            if(self::$constantCacheArray == NULL){
                self::$constantCacheArray = [];
            }
            $calledClass = get_called_class();
            if(!array_key_exists($calledClass, self::$constantCacheArray)){
                $reflect = new ReflectionClass($calledClass);
                self::$constantCacheArray[$calledClass] = $reflect->getConstants();
            }
            return self::$constantCacheArray[$calledClass];
        }

        public static function isValidName($name, $strict = false){
            $constants = self::getConstants();
            if($strict){
                return array_key_exists($name, $constants);
            }
            $keys = array_map('strtolower', array_keys($constants));
            return in_array(strtolower($name), $keys);
        }

        public static function isValidValue($value, $strict = true){
            $values = array_values(self::getConstants());
            return in_array($value, $values, $strict);
        }
    }
?>