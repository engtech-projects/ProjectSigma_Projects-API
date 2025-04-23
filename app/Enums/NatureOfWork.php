<?php

namespace App\Enums;

use App\Enums\Traits\EnumHelper;

enum NatureOfWork: string
{
    use EnumHelper;
    case FLOOD_CONTROL = 'FLOOD CONTROL';
    case ASPHALT = 'ASPHALT';
    case ROAD_CONCRETING = 'ROAD CONCRETING';
    case BRIDGE = 'BRIDGE';
    case PORTS = 'PORTS';
    case OFFICE = 'OFFICE';
    case SLOPE_PROTECTION = 'SLOPE PROTECTION';
    case BRIDGE_REHABILITATION = 'BRIDGE REHABILITATION';
    case FCSPS = 'Flood Control: Construction - Slope Protection using Structural Measures (e.g. Revetment, Retaining Structures, Wirenet)';
    case RCP_ROADS = 'CONSTRUCTION- PCCP';
}
