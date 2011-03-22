<?php

/*
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *   http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

class HeraldObjectTranscript {

  protected $fbid;
  protected $type;
  protected $name;
  protected $fields;

  public function setFBID($fbid) {
    $this->fbid = $fbid;
    return $this;
  }

  public function getFBID() {
    return $this->fbid;
  }

  public function setType($type) {
    $this->type = $type;
    return $this;
  }

  public function getType() {
    return $this->type;
  }

  public function setName($name) {
    $this->name = $name;
    return $this;
  }

  public function getName() {
    return $this->name;
  }

  public function setFields(array $fields) {
    $this->fields = $fields;
    return $this;
  }

  public function getFields() {
    return $this->fields;
  }
}
