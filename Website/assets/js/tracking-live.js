$(function() {
    /**
     Loads on page ready action
     **/
    $(document).ready(function(){
        /**
         Creates interval to check back every 2 seconds
         **/
        setInterval(check_live, 2000);
    });

    /**
     Method to check the site for updates and mark user still active
     **/
    function check_live()
    {
        /** Ajax gets info from the site **/
        $.ajax( {
                url: "/ajax.php",
                success: function(result){

                    /** Parses result **/
                    result = JSON.parse(result);

                    /** Updates all info that has been updated **/
                    $("[data-tracker]").each(function () {
                        $value = $(this).html().trim();
                        $code = $(this).attr("data-tracker");
                        $newValue = result["tracking"][$code]+"";
                        if($newValue !== $value ){
                            console.log("Updated "+ $code+ " from "+ $value + " to "+ $newValue)
                            $(this).html($newValue);
                        }
                    });
                }
            }
        );
    }
});