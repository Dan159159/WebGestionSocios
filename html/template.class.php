<?php 

class Template{
    private $content;

    public function __construct($path, array $data) {
        
        ob_start();
        include($path);
        $this->content = ob_get_clean();
        extract($data);
    }
    public function __toString() {
        return $this->content;
    }
}
?>