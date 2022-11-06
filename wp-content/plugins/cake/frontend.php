<?php

function getResults($id = NULL)
{
    $clientCredentials = array('grant_type' => 'client_credentials', 'client_id' => '3', 'client_secret' => 'lX7l8r3YjUCa65f4f35TQKlJPMtHc3NfYRB9HGj6');
    $baseUrl = "http://127.0.0.1:8000/";
    $bearerToken = getBearerToken($baseUrl, $clientCredentials);

    $finalUrl = $baseUrl . 'api/cakes';
    if ($id != NULL) {
        $finalUrl = $finalUrl . '/' . $id;
    }
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $finalUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $bearerToken,
        ),
    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $records = json_decode($response, true);
    return $records['data'];

}


function frontend_listing()
{
    $search = '';
    if (isset($_GET['rc'])) {

        listing_detail($_GET['rc']);
        return;
    }
    if (isset($_POST['submit'])) {
        $search = $_POST['search'];


    }
    listing($search);
}

function listing_detail($record_id)
{
    ?>
    <style>
        .bain-view-detail {
            display: flex;
            flex-direction: column;
            max-width: 500px;
        }

        .bain-view-detail h3 {
            margin-bottom: 10px;
        }
    </style>
    <script>

        function showPrice(name) {
            alert(name + " Purchaing");
        }


    </script>
    <div class="bain-view-detail">
        <?php
        $print = getResults($record_id);
        wp_enqueue_media(); ?>
        <img src='<?php echo wp_get_attachment_url($print['attachment_id']); ?>' width='600'>
        <h3><?php echo $print['name']; ?></h3>
        <p><?php echo $print['recipe']; ?></p>
        <button value="Purchase" onclick="showPrice('<?php echo $print['name']; ?>')" class="button-primary">Purchase
        </button>

    </div>
    <?php

    //}
}

function listingQuery($search = NULL, $id = NULL)
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'cakes_list';
    $query = "SELECT * FROM $table_name WHERE 1=1";
    if ($search != NULL && $search != "") {
        $query = $query . "  AND name LIKE '%$search%' ";
    }
    if ($id != NULL) {
        $query = $query . "  AND id = '$id' ";
    }
    $result = $wpdb->get_results($query);
    return $result;

}

function listing($search)
{ ?>
    <style>
        search-box {
            margin-bottom: 2px;
            display: flex;
        }

        input {
            margin-bottom: 4px;
        }

        .bain-item {
            display: flex;
            flex-direction: column;
            border-bottom: 1px solid;
            padding: 20px;
            align-items: center;
        }
    </style>


    <form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
        <div class="search-box">
            <input type="text" name="search" value="<?php echo $search; ?>">
            <input type="submit" name="submit" value="Search"/>
        </div>


    </form>
    <?php
    processRecords($search);
}

function processRecords($search)
{

    $params = $search;
    if ($params != "") {
        $params = '?search=' . $search;
    }
    $result = getResults('?search=' . $search);
    foreach ($result as $print) {
        ?>
        <div class="bain-item">
            <h3><?php echo $print['name']; ?></h3>
            <p><?php echo $print['recipe']; ?> </p>
            <a href="<?php echo $_SERVER['REQUEST_URI']; ?>/?rc=<?php echo $print['id']; ?>">Detail</a>
            <?php wp_enqueue_media(); ?>
            <img src='<?php echo wp_get_attachment_url($print['attachment_id']); ?>' width='400'>


        </div>
        <?php
    }

}

function getBearerToken($baseUrl, $clientCredentials)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $baseUrl . 'oauth/token',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $clientCredentials,

    ));

    $response = curl_exec($curl);

    curl_close($curl);
    $response = json_decode($response, true);
    return $response['access_token'];
    print_r($response);
    // $response = json_encode($response);
    echo '-----------------------------';
    // print_r($response);

    echo '-----------------------------';
    $response = json_decode($response, true);
    print_r($response['access_token']);
}
