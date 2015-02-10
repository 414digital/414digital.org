<?php
class WebController implements RestController {
    
    function execute(RestServer $rest) 
    {
        return new GenericView("home.php");
    }
}
?>