<?php

function pr_value($object, $propertyName) {
  return property_exists($object, $propertyName) ? $object->$propertyName : null;
}

?>
