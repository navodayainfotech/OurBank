<?php
/*
############################################################################
#  This file is part of OurBank.
############################################################################
#  OurBank is free software: you can redistribute it and/or modify
#  it under the terms of the GNU Affero General Public License as
#  published by the Free Software Foundation, either version 3 of the
#  License, or (at your option) any later version.
############################################################################
#  This program is distributed in the hope that it will be useful,
#  but WITHOUT ANY WARRANTY; without even the implied warranty of
#  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#  GNU Affero General Public License for more details.
############################################################################
#  You should have received a copy of the GNU Affero General Public License
#  along with this program.  If not, see <http://www.gnu.org/licenses/>.
############################################################################
*/
?>

<?php
class Membership_Model_Individualclone extends Zend_Db_Table {
    protected $_name = 'ourbank_membername';

    public function getHeadOffice() {

        $db = $this->getAdapter();
        $sql = "select office_id, office_name, officetype_id, parentoffice_id 
                from 
                ourbank_officenames where officetype_id != 
                (select officetype_id from ourbank_officehierarchy where Hierarchy_level = (select max(Hierarchy_level) from ourbank_officehierarchy))";

        return $db->fetchAll($sql);

    }

    public function getBranchOffice() {

         $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_officenames')
                        ->where('recordstatus_id = 3 OR recordstatus_id = 1');
            return $result = $this->fetchAll($select);
    }

    public function getBranchConfirm($office_id) {

         $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_officenames')
                        ->where('office_id = ?',$office_id)
                        ->where('recordstatus_id = 3 OR recordstatus_id = 1');
         return $result = $this->fetchAll($select);
    }

    public function getBranchEdit($office_id) {

        $db = $this->getAdapter();
        $sql = "select office_name, office_id from ourbank_officenames where parentoffice_id=$office_id";

        return $db->fetchAll($sql);

    }

    public function getSalutation() {

         $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_salutation');
            //die($select->__toString());
            return $result = $this->fetchAll($select);
    }
 
    public function getGender() {

         $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_gender');
         return $result = $this->fetchAll($select);
    }

    public function getMaritalStatus() {

         $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_membermaritalstatus');
         return $result = $this->fetchAll($select);
    }

    public function getPhysicalStatus() {

         $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_memberphysicalstatus');
         return $result = $this->fetchAll($select);
    }

    public function getLivingassets() {

         $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_otherassets')
                        ->where('assets_id = 1');
         return $result = $this->fetchAll($select);
    }

    public function getNonlivingassets() {

         $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_otherassets')
                        ->where('assets_id = 2');
         return $result = $this->fetchAll($select);
    }

    public function addMember($input = array()) {
        $db = $this->getAdapter();
        $db->insert('ourbank_members',$input);
        return $db->lastInsertId('ourbank_members');
    } 

    public function updateMembercode($memberId,$input = array()) {
	$where[] = "member_id = '".$memberId."'";
	$db = $this->getAdapter();
        $result = $db->update('ourbank_members',$input,$where);

    }

    public function addMembername($data) {
        $this->insert($data);
        return;
    }

    public function addPhoto($data) {
        $db = $this->getAdapter();
        $db->insert('ourbank_memberpicture',$data);
        return $result;
    } 

    public function addMemberaddress($data) {
        $db = $this->getAdapter();
        $db->insert('ourbank_memberaddress',$data);
        return $result;
    } 

    public function addMemberassets($data) {
        $db = $this->getAdapter();
        $db->insert('ourbank_memberotherassets',$data);
        return $result;
    } 

    public function addFamily($data) {
        $db = $this->getAdapter();
        $db->insert('ourbank_familymember',$data);
        return $result;
    } 

    public function addFamilymemberdetails($data) {
        $db = $this->getAdapter();
        $db->insert('ourbank_familymemberdetails',$data);
        return $result;
    } 

    public function addFamilyHealth($data) {
        $db = $this->getAdapter();
        $db->insert('ourbank_familyhealth',$data);
        return $result;
    } 

    public function addFamilyEconomicStatus($data) {
        $db = $this->getAdapter();
        $db->insert('ourbank_familyeconomicstatus',$data);
        return $result;
    } 

    public function addFamilyEducation($data) {
        $db = $this->getAdapter();
        $db->insert('ourbank_familyeducation',$data);
        return $result;
    } 

    public function getMemberDetails() {
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ourbank_members'),array('member_id'))
			->where('a.member_status = 3 OR a.member_status = 1')
			->join(array('b' => 'ourbank_membername'),'a.member_id = b.member_id')
                        ->where('b.recordstatus_id = 3')
                       	->join(array('c' => 'ourbank_officenames'),'a.memberbranch_id = c.office_id')
                        ->where('c.recordstatus_id = 3');
	//die($select->__toString());		
	return $this->fetchAll($select);
    }

    public function viewMember($memberId) {
	$select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ourbank_members'),array('member_id'))
                        ->where('a.member_id = ?',$memberId)
			->where('a.member_status = 3 OR a.member_status = 1')
			->join(array('b' => 'ourbank_membername'),'a.member_id = b.member_id')
                        ->where('b.recordstatus_id = 3')
                       	->join(array('c' => 'ourbank_memberaddress'),'a.member_id = c.member_id')
                        ->where('c.recordstatus_id = 3')
                        ->join(array('d' => 'ourbank_familymemberdetails'),'a.member_id = d.member_id')
                        ->where('d.recordstatus_id = 3')
                        ->join(array('h' => 'ourbank_officenames'),'b.memberoffice_id = h.office_id')
                        ->where('h.recordstatus_id = 3 OR h.recordstatus_id = 1')
                        ->join(array('i' => 'ourbank_officehierarchy'),'h.officetype_id = i.officetype_id')
                        ->join(array('j' => 'ourbank_salutation'),'b.membertitle = j.salutation_id')
                        ->join(array('k' => 'ourbank_memberstatus'),'a.member_status = k.memberstatus_id');
	return $this->fetchAll($select);
    }

    public function memberotherassets($memberId) {

       	$select = $this->select()
		->setIntegrityCheck(false)  
		->join(array('a' => 'ourbank_memberotherassets'),array('memberotherassests_id'))
                ->where('a.member_id = ?',$memberId)
                ->where('a.recordstatus_id = 3')
		->join(array('b' => 'ourbank_otherassets'),'a.otherassests_id = b.otherassets_ID');
	return $this->fetchAll($select);
    }

    public function familymembersView($memberId) {
        $select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ourbank_familymemberdetails'),array('familymemberdetails_id '))
                        ->where('a.member_id = ?',$memberId)
			->where('a.recordstatus_id = 3')
			->join(array('b' => 'ourbank_gender'),'a.gender = b.gender_id')
                        ->join(array('c' => 'ourbank_familyrelationship'),'a.Relationship = c.relationship_ID')
                        ->join(array('d' => 'ourbank_memberphysicalstatus'),'d.memberphysicalstatus_id = a.memberphysicalstatus_id')
                        ->join(array('e' => 'ourbank_membermaritalstatus'),'e.membermaritalstatus_id = a.membermaritalstatus_id')
                        ->join(array('f' => 'ourbank_memberphysicalstatus'),'f.memberphysicalstatus_id = a.memberphysicalstatus_id');

        return $this->fetchAll($select);
    }

    public function familyhealthView($memberId) {
        $select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ourbank_familymemberdetails'),array('familymemberdetails_id '))
                        ->where('a.recordstatus_id = 3')
                        ->join(array('b' => 'ourbank_familyhealth'),'a.member_id = b.member_id')
                        ->where('b.member_id = ?',$memberId)
                        ->where('a.familymember_ID = b.familymember_ID AND b.recordstatus_id = 3')
                        ->join(array('c' => 'ourbank_familyhealth_problem'),'b.health_problem = c.healthproblem_id')
                        ->join(array('d' => 'ourbank_healthundertreatment'),'b.under_treatment = d.under_treatment')
                        ->join(array('e' => 'ourbank_clinicalaccessability'),'b.clinical_accessability = e.clinical_accessability')
                        ->group('b.familyhealth_ID');
// die($select->__toString($select));
        return $this->fetchAll($select);
    }

    public function familyeconomicalView($memberId) {
        $select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ourbank_familymemberdetails'),array('familymemberdetails_id '))
                        ->where('a.recordstatus_id = 3')
                        ->join(array('b' => 'ourbank_familyeconomicstatus'),'a.member_id = b.member_id')
                        ->where('b.member_id = ?',$memberId)
                        ->where('a.familymember_ID = b.familymember_ID AND b.recordstatus_id = 3')
                        ->join(array('c' => 'ourbank_familyearning'),'b.earnings = c.earnings')
                        ->join(array('d' => 'ourbank_familyprofession'),'b.profession = d.profession_ID')
                        ->join(array('e' => 'ourbank_mfibenefits'),'b.mfi_benefits = e.mfi_benefits');;
        return $this->fetchAll($select);
    }

    public function familyeducationView($memberId) {
        $select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ourbank_familymemberdetails'),array('familymemberdetails_id '))
                        ->where('a.recordstatus_id = 3')
                        ->join(array('b' => 'ourbank_familyeducation'),'a.member_id = b.member_id')
                        ->where('b.member_id = ?',$memberId)
                        ->where('a.familymember_ID = b.familymember_ID AND b.recordstatus_id = 3')
                        ->join(array('c' => 'ourbank_familyqualification'),'b.qualification = c.qualification')
                        ->join(array('d' => 'ourbank_schoollocation'),'b.school_location = d.school_location')
                        ->join(array('e' => 'ourbank_transporationforschool'),'b.transporation_for_school = e.transporation_for_school');;

         return $this->fetchAll($select);
    }
    
    public function getStatus() {
        $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_memberstatus');

        return $result = $this->fetchAll($select);
    }

    public function getRelationship() {
        $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_familyrelationship');

        return $result = $this->fetchAll($select);
    }

    public function getHealthproblem() {
        $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_familyhealth_problem');
        return $result = $this->fetchAll($select);
    }

    public function getUndertreatment() {
        $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_healthundertreatment');

        return $result = $this->fetchAll($select);
    }

    public function getClinicalaccessability() {
        $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_clinicalaccessability');

        return $result = $this->fetchAll($select);
    }

    public function getEarning() {
        $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_familyearning');

        return $result = $this->fetchAll($select);
    }

    public function getProfession() {
        $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_familyprofession');

        return $result = $this->fetchAll($select);
    }

    public function getMfibenefits() {
        $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_mfibenefits');

        return $result = $this->fetchAll($select);
    }

    public function getQualification() {
        $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_familyqualification');

        return $result = $this->fetchAll($select);
    }

    public function getSchoollocation() {
        $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_schoollocation');

        return $result = $this->fetchAll($select);
    }

    public function getTransporation() {
        $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_transporationforschool');

        return $result = $this->fetchAll($select);
    }

 
    public function memberStatusUpdate($memberId,$data) {
	$where = 'member_id = '.$memberId;
	$db = $this->getAdapter();
        $db->update('ourbank_members', $data , $where);

    }

    public function familyMemberUpdate($feildname,$table,$pk,$data)
    {
	$pk = intval($pk);
	$db = $this->getAdapter();
        $where[] = "$feildname = '".$pk."'";
	$result = $db->update($table,$data,$where);
        return $result;
    }

    public function findMax($memberId) {
        $db = $this->getAdapter();
        $db->setFetchMode(Zend_Db::FETCH_OBJ);
        return $db->fetchRow("SELECT max(familymember_ID) FROM ourbank_familymember where member_id = '$memberId'");
    }

    public function memberEdit($id,$input = array()) {
        $db = $this->getAdapter();
        $where[] = "member_id = '".$id."'";
        $where[] = "recordstatus_id = '3'";
        $db->update('ourbank_memberaddress',$input,$where);
        $db->update('ourbank_membername',$input,$where);
       
    }

    public function getOfficeId($memberId) {
        
        $select = $this->select()
                        ->setIntegrityCheck(false)  
                        ->from('ourbank_members')
                        ->where('member_id = ?', $memberId);
 
        return $result = $this->fetchAll($select);
    }

    public function memberDelete($id,$data) {

         $db = $this->getAdapter();	
         $where[] = "member_id = '".$id."'";
         $where[] = "member_status = '3'";
         $db->update('ourbank_members',$data,$where);

         return; 
    }

    public function familyMemberDelete($feildname,$table,$pk,$input = array())  {
	
        $db = $this->getAdapter();	
        $pk = intval($pk);
        $where[] = "$feildname = '".$pk."'";
	$where[] = "recordstatus_id = '3'";
	$db->update($table,$input,$where);
        return; 
    }

    public function memberSearch($post) {


                $select = $this->select()
			->setIntegrityCheck(false)  
			->join(array('a' => 'ourbank_membername'),array('membername_id'))
                        ->where('a.memberfirstname like "%" ? "%"',$post['field2'])
                        ->where('a.memberlastname like "%" ? "%"',$post['field4'])
			->where('a.recordstatus_id = 3')
			->join(array('b' => 'ourbank_members'),'a.member_id = b.member_id')
                        ->where('b.membercode like "%" ? "%"',$post['field3'])
                        ->where('b.memberbranch_id like "%" ? "%"',$post['field1'])
			->join(array('c' => 'ourbank_officenames'),'b.memberbranch_id = c.office_id')
                        ->where('c.recordstatus_id = 3');
        return $this->fetchAll($select);
	
        }
}
