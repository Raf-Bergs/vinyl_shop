<h1>Records</h1>

<ul>
    <?php
    foreach ($records as $record){
        echo "<li> $record </li>";
        //echo '<li>' . $record . '</li>';
    }
    ?>

    <li>------------------------</li>


    @foreach($records as $key => $record)
        <li>Record {{$key}}: {{$record}}</li>
   @endforeach
</ul>
