<?php
    date_default_timezone_set('Africa/Nairobi');
    $date = new DateTime('now');
    $date = $date->format('Y-m-d\TH:i:sp');//.substr($date->format('u'), 0, 3).'Z';
    //$date = Date('m/d/Y h:i:s a');
    print_r($date);
    echo "<br>";
    print_r(time() * 1000);
    $time = time();
?>

<script>
    var d = '<?= $date; ?>';
    var t = <?= $time; ?>;

    var userGeometry = Intl.DateTimeFormat().resolvedOptions()

    //var userTimeZone = new Date(d).toLocaleString(userGeometry.locale, {timeZone: userGeometry.timeZone, timeZoneNAme: "short"})
    var options = {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
    }
    var date = new Date(d)

    console.log(date.getTime())
    console.log(new Date().getTime())
    console.log(date.toLocaleString('en-us', options))



    /*console.log(date);
    console.log(date.getTime())
    console.log(new Date().getTime())*/
</script>