<?php

    use DynamicalWeb\Runtime;

    Runtime::import('msqg');

    /**
     * Gets the total Items from a query
     *
     * @param mysqli $mysqli
     * @param string $table
     * @param string $by
     * @param string $where
     * @param string $where_value
     * @return int
     * @throws Exception
     */
    function get_total_items(mysqli $mysqli, string $table, string $by='id', $where=null, $where_value=null): int
    {
        $by = $mysqli->real_escape_string($by);
        $table = $mysqli->real_escape_string($table);

        /** @noinspection SqlNoDataSourceInspection */
        /** @noinspection SqlResolve */
        $Query = "SELECT COUNT($by) AS total FROM `$table`";

        if($where !== null)
        {
            if($where_value == null)
            {
                throw new Exception("'where_value' cannot be null");
            }

            $where = $mysqli->real_escape_string($where);
            $where_value = $mysqli->real_escape_string($where_value);
            $Query .= " WHERE $where='$where_value'";
        }
        $QueryResults = $mysqli->query($Query);

        if($QueryResults == false)
        {
            throw new Exception($mysqli->error);
        }
        else
        {
            return (int)$QueryResults->fetch_array()['total'];
        }
    }

    /**
     * Calculates the max amount of pages
     *
     * @param int $total_items
     * @param int $max_items_per_page
     * @return int
     */
    function total_pages(int $total_items, int $max_items_per_page): int
    {
        if($total_items == 0)
        {
            return 0;
        }

        if($total_items > $max_items_per_page)
        {
            return ceil($total_items / $max_items_per_page);
        }

        return 1;
    }

    /**
     * Gets the current offset depending on the current page
     *
     * @param int $max_items_per_page
     * @param int $current_page
     * @param int $total_pages
     * @return int
     */
    function get_offset(int $max_items_per_page, int $current_page, int $total_pages): int
    {
        if($current_page > $total_pages)
        {
            return ceil($max_items_per_page * ($total_pages -1));
        }

        if($current_page > 1)
        {
            return ceil($max_items_per_page * ($current_page -1));
        }

        return 0;
    }


    /**
     * @param mysqli $mysqli
     * @param $max_items_page
     * @param $table
     * @param $by
     * @param $query
     * @param null $where
     * @param string|null $where_value
     * @return array
     * @throws Exception
     */
    function get_results(mysqli $mysqli, $max_items_page, $table, $by, $query, $where=null, $where_value=null): array
    {
        $CurrentPage = 1;
        if(isset($_GET['page']))
        {
            if((int)$_GET['page'] > 1)
            {
                $CurrentPage = (int)$_GET['page'];
            }
        }

        $TotalItems = get_total_items($mysqli, $table, $by, $where, $where_value);
        $TotalPages = total_pages($TotalItems, $max_items_page);
        $Offset = get_offset($max_items_page, $CurrentPage, $TotalPages);

        $ResultsArray = [];

        $query = substr_replace($query, '', -1);
        $query .= " LIMIT " . (int)$Offset . ", " . (int)$max_items_page;
        $QueryResults = $mysqli->query($query);
        if($QueryResults == false)
        {
            throw new Exception($mysqli->error);
        }
        else
        {

            while ($Row = $QueryResults->fetch_assoc())
            {
                $ResultsArray[] = $Row;
            }
        }

        return array(
            'max_items_per_page' => $max_items_page,
            'total_items' => $TotalItems,
            'total_pages' => $TotalPages,
            'current_page' => $CurrentPage,
            'offset' => $Offset,
            'results' => $ResultsArray
        );
    }