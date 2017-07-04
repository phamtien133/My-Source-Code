package pojos;
// Generated Apr 12, 2017 3:51:30 PM by Hibernate Tools 4.3.1


import java.util.HashSet;
import java.util.Set;

/**
 * TbStudents generated by hbm2java
 */
public class TbStudents  implements java.io.Serializable {


     private String studentsId;
     private TbLogin tbLogin;
     private String studentsName;
     private Set tbSubjectsStudentses = new HashSet(0);

    public TbStudents() {
    }

	
    public TbStudents(String studentsId, TbLogin tbLogin, String studentsName) {
        this.studentsId = studentsId;
        this.tbLogin = tbLogin;
        this.studentsName = studentsName;
    }
    public TbStudents(String studentsId, TbLogin tbLogin, String studentsName, Set tbSubjectsStudentses) {
       this.studentsId = studentsId;
       this.tbLogin = tbLogin;
       this.studentsName = studentsName;
       this.tbSubjectsStudentses = tbSubjectsStudentses;
    }
   
    public String getStudentsId() {
        return this.studentsId;
    }
    
    public void setStudentsId(String studentsId) {
        this.studentsId = studentsId;
    }
    public TbLogin getTbLogin() {
        return this.tbLogin;
    }
    
    public void setTbLogin(TbLogin tbLogin) {
        this.tbLogin = tbLogin;
    }
    public String getStudentsName() {
        return this.studentsName;
    }
    
    public void setStudentsName(String studentsName) {
        this.studentsName = studentsName;
    }
    public Set getTbSubjectsStudentses() {
        return this.tbSubjectsStudentses;
    }
    
    public void setTbSubjectsStudentses(Set tbSubjectsStudentses) {
        this.tbSubjectsStudentses = tbSubjectsStudentses;
    }




}

