package pojos;
// Generated Apr 12, 2017 3:51:30 PM by Hibernate Tools 4.3.1


import java.util.HashSet;
import java.util.Set;

/**
 * TbSubjects generated by hbm2java
 */
public class TbSubjects  implements java.io.Serializable {


     private String subjectsId;
     private String subjectsName;
     private Set tbSubjectsStudentses = new HashSet(0);
     private Set tbSchedules = new HashSet(0);

    public TbSubjects() {
    }

	
    public TbSubjects(String subjectsId, String subjectsName) {
        this.subjectsId = subjectsId;
        this.subjectsName = subjectsName;
    }
    public TbSubjects(String subjectsId, String subjectsName, Set tbSubjectsStudentses, Set tbSchedules) {
       this.subjectsId = subjectsId;
       this.subjectsName = subjectsName;
       this.tbSubjectsStudentses = tbSubjectsStudentses;
       this.tbSchedules = tbSchedules;
    }
   
    public String getSubjectsId() {
        return this.subjectsId;
    }
    
    public void setSubjectsId(String subjectsId) {
        this.subjectsId = subjectsId;
    }
    public String getSubjectsName() {
        return this.subjectsName;
    }
    
    public void setSubjectsName(String subjectsName) {
        this.subjectsName = subjectsName;
    }
    public Set getTbSubjectsStudentses() {
        return this.tbSubjectsStudentses;
    }
    
    public void setTbSubjectsStudentses(Set tbSubjectsStudentses) {
        this.tbSubjectsStudentses = tbSubjectsStudentses;
    }
    public Set getTbSchedules() {
        return this.tbSchedules;
    }
    
    public void setTbSchedules(Set tbSchedules) {
        this.tbSchedules = tbSchedules;
    }




}

