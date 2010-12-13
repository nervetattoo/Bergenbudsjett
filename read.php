<?php
$return = array();

/* Extremely naive implementation of a open office document parser */
$xml = simplexml_load_file('zip://resources/budsjett.ods#content.xml');

$i = 0;
$skipFirst = true;
foreach ($xml->xpath('//table:table') as $table) {
    foreach ($table->xpath('table:table-row') as $row) {
        if ($skipFirst)
        {
            $skipFirst = false;
            continue;
        }

        $values = array();
        $cells = $row->xpath('table:table-cell/text:p');
        foreach ($cells as $val) {
            $value = strip_tags((string) $val->asXml());
            $values[] = trim($value);
        }

        if (count($values) == 5)
        {
            /**
             * Is data row OR total row
             */
            list($id, $desc, $b2011, $b2010, $b2009) = $values;
            if (is_numeric($id))
                $group['items'][] = compact('id','desc','b2011','b2010','b2009');
        }
        elseif (count($values) == 6)
        {
            /**
             * Is grouping row
             */
            if (isset($group))
                $groups[] = $group;
            list($name, $id, $desc, $b2011, $b2010, $b2009) = $values;
            $group = array(
                'name' => $name,
                'items' => array(
                    compact('id', 'desc', 'b2011', 'b2010', 'b2009')
                )
            );
        }
    }
    break;
}
print_R($groups);
