<?php

function clean($string)
{
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.

    return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
$txt = "􀂖􂜁􀄶two􏿿􂜁􀄶two􏿿􂜁􀄓S􏿿􂜁􀄟e􏿿􂜁􀄪p􏿿􂠁􀄵one􏿿􂠁􀄽nine􏿿􂠁􀄼eight􏿿􂠁􀄽nine􏿿􀈂􀄺icecream cone􏿿";
echo clean($txt);
