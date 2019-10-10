<?php
    header("content-type: application/json;");
    $url = "https://rfy56yfcwk.execute-api.us-west-1.amazonaws.com/bigcorp/employees/?";

    $query  = explode('&', $_SERVER['QUERY_STRING']);
    $query2  = explode('/', $_SERVER['REQUEST_URI']);
    $ids = end($query2);
    if (strpos($ids, '?')) {
        $id  = explode('?', $ids);
        if (is_numeric(intval($id[0])) && intval($id[0]) > 0) {
            $param = "id=".intval($id[0])."&";
            $url = $url.$param;
        }
    } else if (is_numeric(intval($ids)) && intval($ids) > 0) {
        $param = "id=".intval($ids)."&";
        $url = $url.$param;
    }
    $params = array();
    if($query[0]) {
        foreach( $query as $param )
        {
            if(is_numeric($param)) {
                $name = "id";
                $value = $param;
            } else{
                if (strpos($param, '=') === false) {
                    $param += '=';
                }
                list($name, $value) = explode('=', $param);
            }
            $param = $name."=".$value."&";
            $url = $url.$param;
        }
    }

    $employees = file_get_contents($url);
    $offices = file_get_contents("./offices.json");
    $departments = file_get_contents("./departments.json");
    $managers = file_get_contents("https://rfy56yfcwk.execute-api.us-west-1.amazonaws.com/bigcorp/employees/");

    $employeesArray = json_decode($employees, true);
    $officesArray = json_decode($offices, true);
    $departmentsArray = json_decode($departments, true);
    $managersArray = json_decode($managers, true);

    if ($query[0]) {
        foreach( $query as $param ) {
            if (strpos($param, 'expand') !== false) {
                $values = explode('=', $param);
                $subParams = explode('.', $values[1]);

                $countEmployees = 0;
                foreach ($employeesArray as $employee) {
                    $countParams = 0;
                    foreach ($subParams as $newParam) {
                        if ($newParam == "superdepartment") {
                            foreach ($departmentsArray as $department) {
                                switch ($countParams) {
                                    case 1:
                                        if ($department['id'] == $employeesArray[$countEmployees]["department"][$newParam]) {
                                            $employeesArray[$countEmployees][$subParams[$countParams-1]][$newParam] = $department;
                                        }
                                        break;
                                    case 2:
                                        if ($department['id'] == $employeesArray[$countEmployees]["department"]["superdepartment"][$newParam]) {
                                            $employeesArray[$countEmployees][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam] = $department;
                                        }
                                        break;
                                    case 3:
                                        if ($department['id'] == $employeesArray[$countEmployees]["department"]["superdepartment"]["superdepartment"][$newParam]) {
                                            $employeesArray[$countEmployees][$subParams[$countParams-3]][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam] = $department;
                                        }
                                        break;
                                    default:
                                        $employeesArray[$countEmployees][$subParams[$countParams-1]][$newParam] = $department;
                                        break;
                                }
                            }
                        }
                        if ($newParam == "department") {
                            foreach ($departmentsArray as $department) {
                                switch ($countParams) {
                                    case 0:
                                        if ($department['id'] == $employee[$newParam]) {
                                            $employeesArray[$countEmployees][$newParam] = $department;
                                        }
                                        break;
                                    case 1:
                                        if ($department['id'] == $employeesArray[$countEmployees][$subParams[$countParams-1]][$newParam]) {
                                            $employeesArray[$countEmployees][$subParams[$countParams-1]][$newParam] = $department;
                                        }
                                        break;
                                    case 2:
                                        if ($department['id'] == $employeesArray[$countEmployees][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam]) {
                                            $employeesArray[$countEmployees][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam] = $department;
                                        }
                                        break;
                                    case 3:
                                        if ($department['id'] == $employeesArray[$countEmployees][$subParams[$countParams-3]][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam]) {
                                            $employeesArray[$countEmployees][$subParams[$countParams-3]][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam] = $department;
                                        }
                                        break;
                                    default:
                                        $employeesArray[$countEmployees][$newParam] = $department;
                                        break;
                                }
                            }
                        }
                        if ($newParam == "manager") {
                            foreach ($managersArray as $manager) {
                                switch ($countParams) {
                                    case 0:
                                        if ($manager['id'] == $employee[$newParam]) {
                                            $employeesArray[$countEmployees][$newParam] = $manager;
                                        }
                                        break;
                                    case 1:
                                        if ($manager['id'] == $employeesArray[$countEmployees][$subParams[$countParams-1]][$newParam]) {
                                            $employeesArray[$countEmployees][$subParams[$countParams-1]][$newParam] = $manager;
                                        }
                                        break;
                                    case 2:
                                        if ($manager['id'] == $employeesArray[$countEmployees][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam]) {
                                            $employeesArray[$countEmployees][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam] = $manager;
                                        }
                                        break;
                                    case 3:
                                        if ($manager['id'] == $employeesArray[$countEmployees][$subParams[$countParams-3]][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam]) {
                                            $employeesArray[$countEmployees][$subParams[$countParams-3]][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam] = $manager;
                                        }
                                        break;
                                    default:
                                        $employeesArray[$countEmployees][$newParam] = $manager;
                                        break;
                                }
                            }
                        }
                        if ($newParam == "office") {
                            foreach ($officesArray as $office) {
                                switch ($countParams) {
                                    case 0:
                                        if ($office['id'] == $employee[$newParam]) {
                                            $employeesArray[$countEmployees][$newParam] = $office;
                                        }
                                        break;
                                    case 1:
                                        if ($office['id'] == $employeesArray[$countEmployees][$subParams[$countParams-1]][$newParam]) {
                                            $employeesArray[$countEmployees][$subParams[$countParams-1]][$newParam] = $office;
                                        }
                                        break;
                                    case 2:
                                        if ($office['id'] == $employeesArray[$countEmployees][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam]) {
                                            $employeesArray[$countEmployees][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam] = $office;
                                        }
                                        break;
                                    case 3:
                                        if ($office['id'] == $employeesArray[$countEmployees][$subParams[$countParams-3]][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam]) {
                                            $employeesArray[$countEmployees][$subParams[$countParams-3]][$subParams[$countParams-2]][$subParams[$countParams-1]][$newParam] = $office;
                                        }
                                        break;
                                    default:
                                        $employeesArray[$countEmployees][$newParam] = $office;
                                        break;
                                }
                            }
                        }
                        $countParams++;
                    }
                    $countEmployees++;
                }
            }
        }
    }
    
    $response = json_encode($employeesArray, JSON_FORCE_OBJECT);
    echo $response;
?>