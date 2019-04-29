<?php
    /**
     * Ellie Parobek MySQLDatabase.php: Class to contain all database functions.
     */
    class MySQLDatabase{
        /**
         * Connecting to database.
         * @return: MySQLi connection object for queries.
         */
        public function connect(){
            $server = "localhost";
            $username = "arp6333";
            $password = "twentyexcept";
            $dbname = "arp6333";
            return new mysqli($server, $username, $password, $dbname);
        }

        /**
         * Logging in a user.
         * @param $username: Username of user logging in.
         * @param $password: Password of user logging in.
         */
        public function login($username, $password){
            $conn = $this->connect();
            $query = "SELECT * FROM attendee WHERE name = ? AND password = ?;";
            $stmt = $conn->prepare($query);
            $password = hash("sha256", $password);
            $stmt->bind_param("ss", $username, $password);
            $stmt->execute();
            $response = $stmt->get_result()->fetch_assoc();
            if($response == null){
                $stmt->close();
                $conn->close();
                return -1;
            }
            else{
                // Set session user type.
                $role = $response['role'];
                if($role == 1){
                    $_SESSION['userType'] = "admin";
                }
                else if($role == 2){
                    $_SESSION['userType'] = "manager";
                }
                else{
                    $_SESSION['userType'] = "attendee";
                }
                // Set the session name to the user's ID.
                $_SESSION['username'] = $response['idattendee'];
            }
            header("Location: http://serenity.ist.rit.edu/~arp6333/341/project1/registrations.php");
            $stmt->close();
            $conn->close();
        }

        /**
         * Get event information based on manager associated.
         */
        public function getInfo(){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT event FROM manager_event WHERE manager = '" . $_SESSION["username"] . "';";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $query2 = "SELECT * FROM event WHERE idevent = " . $row . ";";
                $result2 = $conn->query($query2);
                while($row2 = $result2->fetch_assoc()){
                    $array[] = $row2;
                }
            }
            $conn->close();
            return $array;
        }

        /**
         * Insert event into database.
         * @param $name: Name of event.
         * @param $dateStart: Start time. 
         * @param $dateEnd: End time.
         * @param $numberAllowed: Number of people allowed.
         * @param $venue: Venue location.
         * @param $manager: ID of the manager.
         * @return int: Whether insert was successful or not.
         */
        public function insertEvent($name, $dateStart, $dateEnd, $numberAllowed, $venue, $manager){
            $conn = $this->connect();
            $query = "INSERT INTO event SET name = ?, datestart = ?, dateend = ?, numberallowed = ?, venue = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssii", $name, $dateStart, $dateEnd, $numberAllowed, $venue);
            if(!$stmt->execute()){
                $response = -1;
            }
            else{
                $eventId = $conn->insert_id;
                $query = "INSERT INTO manager_event SET event = ?, manager = ?;";
                $stmt = $conn->prepare($query);
                $stmt->bind_param("ii", $eventId, $manager);
                if(!$stmt->execute()){
                    $response = -1;
                }
                else{
                    $response = 1;
                }
            }
            $stmt->close();
            $conn->close();
            return $response;
        }

        /**
         * Set user to attend an event.
         * @param $event: Event ID.
         * @param $attendee: Attendee ID.
         * @return int: Whether insert was successful or not.
         */
        public function attendEvent($event, $attendee){
            $conn = $this->connect();
            $query = "INSERT INTO attendee_event SET event = ?, attendee = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $event, $attendee);
            if(!$stmt->execute()){
                $response = -1;
            }
            else{
                $response = 1;
            }
            $stmt->close();
            $conn->close();
            return $response;
        }
        
        /**
         * Set user to attend a session.
         * @param $event: Event ID.
         * @param $attendee: Attendee ID.
         * @return int: Whether insert was successful or not.
         */
        public function attendSession($session, $attendee){
            $conn = $this->connect();
            $query = "INSERT INTO attendee_session SET session = ?, attendee = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ii", $session, $attendee);
            if(!$stmt->execute()){
                $response = -1;
            }
            else{
                $response = 1;
            }
            $stmt->close();
            $conn->close();
            return $response;
        }
        
        /**
         * Insert session for an event into database.
         * @param $name: Name of session.
         * @param $id: ID of event session is tied to.
         * @param $dateStart: Start time. 
         * @param $dateEnd: End time.
         * @param $numberAllowed: Number of people allowed.
         * @return int: Whether insert was successful or not.
         */
        public function insertSession($name, $id, $dateStart, $dateEnd, $numberAllowed){
            $conn = $this->connect();
            $query = "INSERT INTO session SET name = ?, numberallowed = ?, event = ?, startdate = ?, enddate = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("siiss", $name, $numberAllowed, $id, $dateStart, $dateEnd);
            if(!$stmt->execute()){
                $response = -1;
            }
            else{
                $response = 1;
            }
            $stmt->close();
            $conn->close();
            return $response;
        }
        
        /**
         * Insert new user into database.
         * @param $name: Name of user.
         * @param $password: Password of user.
         $ @param $role: Role of user.
         * @return int: Whether insert was successful or not.
         */
        public function insertUser($name, $password, $role){
            $conn = $this->connect();
            $query = "INSERT INTO attendee SET name = ?, password = ?, role = ?;";
            $password = hash("sha256", $password);
            $stmt = $conn->prepare($query);
            $stmt->bind_param("ssi", $name, $password, $role);
            if(!$stmt->execute()){
                $response = -1;
            }
            else{
                $response = 1;
            }
            $stmt->close();
            $conn->close();
            return $response;
        }
        
        /**
         * Insert new venue into database.
         * @param $name: Name of venue.
         * @param $capacity: Number of people allowed.
         * @return int: Whether insert was successful or not.
         */
        public function insertVenue($name, $capacity){
            $conn = $this->connect();
            $query = "INSERT INTO venue SET name = ?, capacity = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("si", $name, $capacity);
            if(!$stmt->execute()){
                $response = -1;
            }
            else{
                $response = 1;
            }
            $stmt->close();
            $conn->close();
            return $response;
        }

        /**
         * Removes an item from a given table(attendee_event or attendee_session) by ID.
         * @param $table: Table item is to be removed from.
         * @param $id: The ID of the item to be removed.
         * @param $idName: The name of the primary key to be removed.
         * @return: Whether delete was successful or not.
         */
        public function delete($table, $id, $idName){
            $conn = $this->connect();
            $query = "DELETE FROM " . $table . " WHERE " . $idName . " = ? AND attendee = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("is", $id, $_SESSION["username"]);
            if($stmt->execute()){
                $stmt->close();
                $conn->close();
                return 1;
            }
            else{
                $stmt->close();
                $conn->close();
                return -1;
            }
        }

        /**
         * Removes an item from a given table (called by admins only, does not require ID).
         * @param $table: Table item is to be removed from.
         * @param $id: The ID of the item to be removed.
         * @param $idName: The name of the primary key to be removed.
         * @return: Whether delete was successful or not.
         */
        public function adminDelete($table, $id, $idName){
            $conn = $this->connect();
            $query = "DELETE FROM " . $table . " WHERE " . $idName . " = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            if($stmt->execute()){
                $stmt->close();
                $conn->close();
                return 1;
            }
            else{
                $stmt->close();
                $conn->close();
                return -1;
            }
        }

        /**
         * Get user information of all users.
         * @return: Array of data about each user.
         */
        public function getAttendees(){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT * FROM attendee;";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $array[] = $row;
            }
            $conn->close();
            return $array;
        }
        
        /**
         * Get user information of one specific user.
         * @param $id: ID of user to get.
         * @return: Array of data about one user.
         */
        public function getAttendee($id){
            $conn = $this->connect();
            $query = "SELECT * FROM attendee WHERE idattendee = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
        
        /**
         * Get venue information of all venues.
         * @return: Array of data about each venue.
         */
        public function getVenues(){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT * FROM venue;";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $array[] = $row;
            }
            $conn->close();
            return $array;
        }
        
        /**
         * Get venue information of one specific venue.
         * @param $id: ID of venue to get.
         * @return: Array of data about one venue.
         */
        public function getVenue($id){
            $conn = $this->connect();
            $query = "SELECT * FROM venue WHERE idvenue = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
        
        /**
         * Get event information of all events.
         * @return: Array of data about each event.
         */
        public function getEvents(){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT * FROM event;";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $array[] = $row;
            }
            $conn->close();
            return $array;
        }    
        
        /** 
         * Get all events a manager/admin created.
         * @return: Array of events and associated sessions.
         */
        public function getManagerEvents(){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT event FROM manager_event WHERE manager = " . $_SESSION["username"] . ";";
            if($result = $conn->query($query)){
                while(($row = $result->fetch_assoc()) && !($result == false)){
                    $query2 = "SELECT * FROM event WHERE idevent = " . $row['event'] . ";";
                    $result2 = $conn->query($query2);
                    while($row2 = $result2->fetch_assoc()){
                        $array[] = $row2;
                    }
                }
            }
            $conn->close();
            return $array; 
        }
        
        /**
         * Get event information of one specific event.
         * @param $id: ID of event to get.
         * @return: Array of data about one event.
         */
        public function getEvent($id){
            $conn = $this->connect();
            $query = "SELECT * FROM event WHERE idevent = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }
        
        /**
         * Get session information of all sessions.
         * @return: Array of data about each session.
         */
        public function getSessions(){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT * FROM session;";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $array[] = $row;
            }
            $conn->close();
            return $array;
        }
        
        /**
         * Get session information of one specific session.
         * @param $id: ID of session to get.
         * @return: Array of data about one session.
         */
        public function getSession($id){
            $conn = $this->connect();
            $query = "SELECT * FROM session WHERE idsession = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            return $stmt->get_result()->fetch_assoc();
        }

        /**
         * Get session information of one specific session by the event it is tied to.
         * @param $id: ID of event session is tied to.
         * @return: Array of sessions.
         */
        public function getSessionByEvent($id){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT * FROM session WHERE event = " . $id . ";";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $array[] = $row;
            }
            $conn->close();
            return $array;
        }
        
        /**
         * Update user table information.
         * @param $name: Name of user to be changed.
         * @param $role: Role of user to be changed.
         * @param $id: ID of user to be changed.
         */
        public function adminEditUser($name, $role, $id){
            $conn = $this->connect();
            $query = "UPDATE attendee SET name = ?, role = ? WHERE idattendee = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sii", $name, $role, $id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
        
        /**
         * Update event table information.
         * @param $name: Name of event.
         * @param $dateStart: Start time. 
         * @param $dateEnd: End time.
         * @param $numberAllowed: Number of people allowed.
         * @param $venue: Venue location.
         * @param $id: ID of event.
         */
        public function editEvent($name, $dateStart, $dateEnd, $numberAllowed, $venue, $id){
            $conn = $this->connect();
            $query = "UPDATE event SET name = ?, datestart = ?, dateend = ?, numberallowed = ?, venue = ? WHERE idevent = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssiii", $name, $dateStart, $dateEnd, $numberAllowed, $venue, $id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
        
         /**
         * Update session table information.
         * @param $name: Name of session.
         * @param $dateStart: Start time. 
         * @param $dateEnd: End time.
         * @param $numberAllowed: Number of people allowed.
         * @param $event: Event associated.
         * @param $id: ID of session.
         */
        public function editSession($name, $dateStart, $dateEnd, $numberAllowed, $event, $id){
            $conn = $this->connect();
            $query = "UPDATE session SET name = ?, startdate = ?, enddate = ?, numberallowed = ?, event = ? WHERE idsession = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sssiii", $name, $dateStart, $dateEnd, $numberAllowed, $event, $id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }
        
        /**
         * Update venue table information.
         * @param $name: Name of venue.
         * @param $capacity: Number of people allowed.
         * @param $id: ID of venue.
         */
        public function editVenue($name, $capacity, $id){
            $conn = $this->connect();
            $query = "UPDATE venue SET name = ?, capacity = ? WHERE idvenue = ?;";
            $stmt = $conn->prepare($query);
            $stmt->bind_param("sii", $name, $capacity, $id);
            $stmt->execute();
            $stmt->close();
            $conn->close();
        }

        /**
         * Get all events a user is registered to.
         * @param: User to get info on (not entered by user).
         * @return: Array of session info.
         */
        public function getRegisteredEvents($user){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT event FROM attendee_event WHERE attendee = " . $user . ";";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $query2 = "SELECT e.*, v.name FROM event as e LEFT JOIN venue as v ON v.idvenue = e.venue WHERE e.idevent = " . $row["event"] . ";";
                $result2 = $conn->query($query2);
                while($row2 = $result2->fetch_assoc()){
                    $array[] = $row2;
                }
            }
            $conn->close();
            return $array;
        }

        /**
         * Get all sessions a user is registered to.
         * @param: User to get info on (not entered by user).
         * @return: Array of session info.
         */
        public function getRegisteredSessions($user){
            $conn = $this->connect();
            $array = array();
            $query = "SELECT session FROM attendee_session WHERE attendee = " . $user . ";";
            $result = $conn->query($query);
            while($row = $result->fetch_assoc()){
                $query2 = "SELECT s.*, e.name FROM session as s LEFT JOIN event as e ON e.idevent = s.event WHERE s.idsession = " . $row["session"] . ";";
                $result2 = $conn->query($query2);
                while($row2 = $result2->fetch_assoc()){
                    $array[] = $row2;
                }
            }
            $conn->close();
            return $array;  
        }
    }
?>