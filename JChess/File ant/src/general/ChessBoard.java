package general;

import java.awt.Point;
import java.awt.event.ActionEvent;
import java.awt.event.ActionListener;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.io.BufferedReader;
import java.io.IOException;
import java.io.InputStreamReader;
import java.io.PrintWriter;
import java.net.ServerSocket;
import java.net.Socket;
import java.net.UnknownHostException;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Random;
import java.util.logging.Handler;
import java.util.logging.LogRecord;
import javax.swing.JButton;
import javax.swing.JLabel;
import javax.swing.JOptionPane;
import javax.swing.JPanel;

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author VuQuang
 */
public class ChessBoard extends JPanel implements MouseListener{
    
    public static Boolean kingsideCastling = true;//Nhập thành gần, quân đen
    public static Boolean queensideCastling = true;//Nhập thành xa, quân đen
        
     public static Boolean KINGsideCastling = true;//Nhập thành gần, quân trắng        
    public static Boolean QUEENsideCastling = true;//Nhập thành xa, quân trắng
    
    //Network
    private boolean Iam_Server=false;
    private boolean Iam_Client=false;
    private ServerSocket ServerSock;
    private Socket Sock;
    private BufferedReader in;
    private PrintWriter out;
    private String Box;
    private String MyIp_Address;
    private String MyPort_number;
    private Recv_Thread Recv_from;
    private boolean Game_started=true;
    
    public static Point _EnPassantPoint = new Point();
    public ArrayList arrMove = new ArrayList();//ds move co the di
    Boolean isMoving = false;
    private ChessSide _OwnSide = ChessSide.White;
    private GameMode _GameMode;
    private Boolean WhiteToMove = true;
    private int _CellSize, _PieceSize;//Kích thước ô cờ, quân cờ
    
    private ChessCell _PickedCell = null;
    
    public int[][] _BoardState = new int[10][10];//Luu trang thai ban co
    /*
     * Qui ước: 
     *  + Cạnh bàn cờ:-1
     *  + Không có quân cờ:0
     *  + Quân Tốt Đen:   11, Quân Tốt Trắng:   12
     *  + Quân Tượng Đen: 21, Quân Tượng Trắng: 22
     *  + Quân Mã Đen:    31, Quân Mã Trắng:    32
     *  + Quân Xe Đen:    41, Quân Xe Trắng:    42
     *  + Quân Hậu Đen:   51, Quân Hậu Trắng:   52
     *  + Quân Vua Đen:   61, Quân Vua Trắng:   62
     */


    public ChessCell[][] arrChessCell = new ChessCell[9][9];//mảng các ô cờ
    
    // "Tạo bàn cờ từ các ô cờ"
    /*
         * Góc dưới bên trái của bàn cờ vua là một ô đen
         * Quân Hậu Trắng sẽ nằm trên ô Trắng và Quân Hậu Đen sẽ nằm trên ô đen
         * Bàn cờ được đánh tọa độ như sau :
         * Từ A->I tính từ trái sang phải
         * Từ 1->8 tính từ dưới lên bắt đầu từ dòng đầu tiên của quân trắng             * 
         */

    /*
     * Nếu x+y là số lẻ thì đó là ô Trắng
     * Nếu x+y là số chẵn thì đó là ô Đen
     */
    
    public ChessBoard(ChessSide eOwnSide, GameMode eGameMode, int CellSize)
    {
        //InitializeComponent();
        //super();
        this.setLayout(null);
        this.setLocation(new Point(50,0));
        this.setSize(CellSize * 8, CellSize * 8);

        this._CellSize = CellSize;
        this._OwnSide = eOwnSide;
        this._GameMode = eGameMode;

        CreateChessBoard();//Tạo các ô cờ    
        kingsideCastling = true;//Nhập thành gần, quân đen
        queensideCastling = true;//Nhập thành xa, quân đen

        KINGsideCastling = true;//Nhập thành gần, quân trắng        
        QUEENsideCastling = true;//Nhập thành xa, quân trắng
        _EnPassantPoint = new Point();
        //clsFEN.SetFEN(this, strFEN);


    }
    
    public void CreateChessBoard()
    {
        InitBoardState();

        for (int i = 1; i <= 8; i++)//-> tạo ô cờ từ trên xuông dưới từ trái sang phải
        {
            for (int j = 1; j <= 8; j++)
            {

                ChessCell Cell = new ChessCell();
                if ((9 - i + j) % 2 == 1)//ô Trắng
                {
                    Cell = new ChessCell(j, 9 - i, CellColor.White,_CellSize);
                    
                }
                else
                {
                    Cell = new ChessCell(j, 9 - i, CellColor.Black,_CellSize);
                }
                
                if (_BoardState[j][i] > 0)
                {
                    ChessPieceType eType = ChessPieceType.getName(_BoardState[j][i] / 10);
                    ChessSide eSide = ChessSide.getName(_BoardState[j][i] % 10);
                    Cell = new ChessCell(j, 9 - i, Cell.getColor(),eSide,eType,_CellSize);
                }
                //Cell.setSize(this._CellSize, this._CellSize);
                Cell.setName(String.valueOf((char)(64 + j)) + String.valueOf(9 - i));//65 là kí tự A
                if (this._OwnSide == ChessSide.White)
                {
                    Cell.setLocation(new Point((j - 1) * _CellSize, (i - 1) * _CellSize));
                }
                else
                {
                    Cell.setLocation(new Point((8 - j) * _CellSize, (8 - i) * _CellSize));
                }

                Cell.addMouseListener(this); //Bat su kien click

                arrChessCell[j][9 - i] = Cell;//Đưa vào mảng các ô cờ

                
                this.add(Cell);
            }
        }
    }
    
    //Create _BoardState
    private void InitBoardState()
    {
        /*
        * Qui ước:             
        *  + Cạnh bàn cờ:-1
        *  + Không có quân cờ:0
        *  + Quân Tốt Đen:   11, Quân Tốt Trắng:   12
        *  + Quân Tượng Đen: 21, Quân Tượng Trắng: 22
        *  + Quân Mã Đen:    31, Quân Mã Trắng:    32
        *  + Quân Xe Đen:    41, Quân Xe Trắng:    42
        *  + Quân Hậu Đen:   51, Quân Hậu Trắng:   52
        *  + Quân Vua Đen:   61, Quân Vua Trắng:   62
        * 
        * Phần tử có tọa độ (x,y) có trạng thái là _BoardState[x,y]
        */

        //******Quan Tot******
        for (int i = 1; i <= 8; i++)
        {
            _BoardState[i][2] = 12;
            _BoardState[i][7] = 11;
        }
        //******Quan Tượng*******
        _BoardState[3][1] = 22;
        _BoardState[6][1] = 22;
        _BoardState[3][8] = 21;
        _BoardState[6][8] = 21;
        //******Quan Mã*******
        _BoardState[2][1] = 32;
        _BoardState[7][1] = 32;
        _BoardState[2][8] = 31;
        _BoardState[7][8] = 31;
        //******Quan Xe*******
        _BoardState[1][1] = 42;
        _BoardState[8][1] = 42;
        _BoardState[1][8] = 41;
        _BoardState[8][8] = 41;
        //******Quan Hậu, Vua*******
        _BoardState[4][1] = 52;
        _BoardState[4][8] = 51;
        _BoardState[5][1] = 62;
        _BoardState[5][8] = 61;
        //******Cạnh bàn cờ*********
        for (int i = 0; i < 10; i++)
        {
            _BoardState[0][i] = -1;
            _BoardState[i][0] = -1;
            _BoardState[9][i] = -1;
            _BoardState[i][9] = -1;
        }
        //*****Còn lại là 0********         
    }
    
    public void HighlightPossibleMoves()
    {
        if (arrMove.size() == 0)
            return;

        for (Object q : arrMove)
        {
            Point p = (Point) q;
            ChessCell cell = this.arrChessCell[p.x][p.y];
            cell.HighlightPossibleMove();
        }

       this.validate();
    }
    
    public void UnHighlightMoves()
    {
        if (arrMove.size() == 0)
            return;

        for (Object q : arrMove)
        {
            Point p = (Point) q;
            ChessCell cell = this.arrChessCell[p.x][p.y];
            cell.UnHighlightMove();
        }

       this.validate();
    }
    
    public Boolean checkWinner() {
        Boolean isBlackWin = true;
        Boolean isWhiteWin = true;
        
        for (int i = 1; i <= 8; i++)//-> tạo ô cờ từ trên xuông dưới từ trái sang phải
        {
            for (int j = 1; j <= 8; j++)
            {
                //Neu tim thay quan vua den thi Trang chua win
                if(_BoardState[i][j] == 61)
                {
                    isWhiteWin = false;
                }
                
                if(_BoardState[i][j] == 62) {
                    isBlackWin = false;
                }
            }
        }
        if(isBlackWin) {
            JOptionPane.showMessageDialog(null, "White is winner", "Winner",JOptionPane.INFORMATION_MESSAGE);
            return true;
        }
        if(isWhiteWin) {
            JOptionPane.showMessageDialog(null, "Black is winner", "Winner",JOptionPane.INFORMATION_MESSAGE);
            return true;
        }
        return false;
    }
    
    public ChessCell getCellFromLocation(Point p) {
        for (int i = 1; i <= 8; i++)//-> tạo ô cờ từ trên xuông dưới từ trái sang phải
        {
            for (int j = 1; j <= 8; j++)
            {
                Point cur = new Point(arrChessCell[i][j]._PositionX,arrChessCell[i][j]._PositionY);
                if(p.x == cur.x && p.y == cur.y) {
                    return arrChessCell[i][j];
                }
            }
        }
        return null;
    }
    
    public void start_As_Server(String Ip,String Port) {
        
        JLabel label = new JLabel("Loading...");
        label.setSize(150,25);
        label.setLocation(new Point());
        label.setVisible(true);
        this.add(label);
        Recv_from=new Recv_Thread();
        Game_started=false;
        
        MyIp_Address=Ip;
        MyPort_number=Port;
                
        try {


            ServerSock=new ServerSocket(Integer.parseInt(MyPort_number));

            Thread Server=new Thread(new Runnable() {
                public synchronized  void run() {

                    try {

                        Sock=ServerSock.accept();
                        in=new BufferedReader(new InputStreamReader(Sock.getInputStream()));
                        out=new PrintWriter(Sock.getOutputStream());
                        Recv_from.start();
                        Game_started=true;
                        if(label.isVisible())
                            label.setVisible(false);

                    } catch (IOException ex) {
                        ex.printStackTrace();
                    }
                }
            });


            Server.start();
            
     /*in=new BufferedReader(new InputStreamReader(Sock.getInputStream()));
     out=new PrintWriter(Sock.getOutputStream());*/
            // Sock.setSoTimeout(999999);
            //  Refe_Chat.listen_chat();



        } catch (IOException ex) {
            ex.printStackTrace();

            JOptionPane.showConfirmDialog(null,"Server error","Error",JOptionPane.ERROR_MESSAGE);
        }
                
        Iam_Server=true;

    }
    public void start_As_Client(String Ip,String Port) {
        
        
        Recv_from=new Recv_Thread();
        Game_started=false;
        
        MyIp_Address=Ip;
        MyPort_number=Port;
        try {

            Sock=new Socket(MyIp_Address,Integer.parseInt(MyPort_number));
            in=new BufferedReader(new InputStreamReader(Sock.getInputStream()));
            out=new PrintWriter(Sock.getOutputStream());



            Recv_from.start();
            Game_started=true;

        } catch (UnknownHostException ex) {
            ex.printStackTrace();
        } catch (IOException ex) {
            ex.printStackTrace();
            JOptionPane.showConfirmDialog(null,"Client error","Error",JOptionPane.ERROR_MESSAGE);
        }
        
        Iam_Client = true;
    }

    @Override
    public void mouseClicked(MouseEvent e) {
        //System.out.print("board1");
    }

    @Override
    public void mousePressed(MouseEvent e) {
        
       // System.out.print("board2");
    }

    @Override
    public void mouseReleased(MouseEvent e) {
        
        //Ktra xem co ai thang chua
        if(checkWinner()) {
            return;
        }
        ChessCell cell = (ChessCell) e.getSource();
        //VsHuman
        if(_GameMode == GameMode.VsHuman) {
            if(isMoving) {
                Point CurPos = new Point(cell._PositionX, cell._PositionY);
                //Neu diem duoc click nam trong cac diem co the di
                if(arrMove.contains(CurPos)) {
                    //Di chuyen con co
                    cell.setImage(this._PickedCell.getImage()); //di chuyen con co den vtri do
                    _PickedCell.setIcon(null); //xoa con co o vi tri cu
                    _PickedCell.validate();

                    cell._Side = _PickedCell._Side;
                    cell._Type = _PickedCell._Type;

                    _PickedCell._Side = null;
                    _PickedCell._Type = null;

                    Point oldPos = new Point(_PickedCell._PositionX, _PickedCell._PositionY);
                    //Xoa trang thai cu, cap nhat trang thai moi
                    this._BoardState[CurPos.x][CurPos.y] = this._BoardState[oldPos.x][oldPos.y];
                    this._BoardState[oldPos.x][oldPos.y] = 0;
                    //Doi luot di
                    this.WhiteToMove = !this.WhiteToMove;
                    if(cell.Promotion(cell._Side)) {
                        if(cell._Side != ChessSide.White)
                            this._BoardState[CurPos.x][CurPos.y] = 52;
                        else
                            this._BoardState[CurPos.x][CurPos.y] = 51;

                        JOptionPane.showMessageDialog(null, "Promoted", "Update",JOptionPane.INFORMATION_MESSAGE);
                    }
                }
                this.UnHighlightMoves();
                isMoving = false;
            }
            else {
                this.arrMove = new ArrayList();
                //Hiển thị các nước có thể đi
                if ((this.WhiteToMove == true && cell._Side == ChessSide.White) || (this.WhiteToMove == false && cell._Side == ChessSide.Black))
                {
                    int[][] BoardState = this._BoardState;//Lấy trạng thái bàn cờ
                    Point CurPos = new Point(cell._PositionX, cell._PositionY);
                    this.arrMove = ChessEngine.FindAllLegalMove(BoardState, CurPos, cell._Type);               
                    this._PickedCell = cell;
                    this.HighlightPossibleMoves();
                    isMoving = true;
                }
            }
        }
        //VsComputer
        else if(_GameMode == GameMode.VsComputer) {
            if((this._OwnSide == ChessSide.White && this.WhiteToMove == true)
                || (this._OwnSide == ChessSide.Black && this.WhiteToMove == false)) {
                if(isMoving) {
                    Point CurPos = new Point(cell._PositionX, cell._PositionY);
                    //Neu diem duoc click nam trong cac diem co the di
                    if(arrMove.contains(CurPos)) {
                        //Di chuyen con co
                        cell.setImage(this._PickedCell.getImage()); //di chuyen con co den vtri do
                        _PickedCell.setIcon(null); //xoa con co o vi tri cu
                        _PickedCell.validate();

                        cell._Side = _PickedCell._Side;
                        cell._Type = _PickedCell._Type;

                        _PickedCell._Side = null;
                        _PickedCell._Type = null;

                        Point oldPos = new Point(_PickedCell._PositionX, _PickedCell._PositionY);
                        //Xoa trang thai cu, cap nhat trang thai moi
                        this._BoardState[CurPos.x][CurPos.y] = this._BoardState[oldPos.x][oldPos.y];
                        this._BoardState[oldPos.x][oldPos.y] = 0;
                        //Doi luot di
                        //this.WhiteToMove = !this.WhiteToMove;
                        
                        //Ramdom Move
                        //Move move = ChessEngine.RandomMove(_BoardState, ChessSide.White);
                        
                        //Difficult Move
                        int[][] BoardState = new int[10][10];
                        //Copy array2d
                        for(int i = 0; i< this._BoardState.length; i++){
                            for (int j = 0; j < this._BoardState.length; j++){
                                BoardState[i][j] = this._BoardState[i][j];
                            }
                        }
                        
                        Move move = ChessEngine.GenerateMove(BoardState, ChessSide.White, 10000);
                        
                        ChessCell ramdomCell = getCellFromLocation(move.getCurPos());
                        ChessCell desCell = getCellFromLocation(move.getNewPos());
                        desCell.setImage(ramdomCell.getImage()); //di chuyen con co den vtri do
                        ramdomCell.setIcon(null); //xoa con co o vi tri cu
                        ramdomCell.validate();

                        desCell._Side = ramdomCell._Side;
                        desCell._Type = ramdomCell._Type;

                        ramdomCell._Side = null;
                        ramdomCell._Type = null;

                        //Xoa trang thai cu, cap nhat trang thai moi
                        this._BoardState[move.getNewPos().x][move.getNewPos().y] = this._BoardState[move.getCurPos().x][move.getCurPos().y];
                        this._BoardState[move.getCurPos().x][move.getCurPos().y] = 0;
                        //Doi luot di
                        //this.WhiteToMove = !this.WhiteToMove;
                        if(cell.Promotion(cell._Side)) {
                            if(cell._Side != ChessSide.White)
                                this._BoardState[CurPos.x][CurPos.y] = 52;
                            else
                                this._BoardState[CurPos.x][CurPos.y] = 51;
                                
                            JOptionPane.showMessageDialog(null, "Promoted", "Update",JOptionPane.INFORMATION_MESSAGE);
                        }
                    }
                    this.UnHighlightMoves();
                    isMoving = false;
                    
                }
                else {
                    this.arrMove = new ArrayList();
                    //Hiển thị các nước có thể đi
                    if ((this.WhiteToMove == true && cell._Side == ChessSide.White) || (this.WhiteToMove == false && cell._Side == ChessSide.Black))
                    {
                        int[][] BoardState = this._BoardState;//Lấy trạng thái bàn cờ
                        Point CurPos = new Point(cell._PositionX, cell._PositionY);
                        this.arrMove = ChessEngine.FindAllLegalMove(BoardState, CurPos, cell._Type);               
                        this._PickedCell = cell;
                        this.HighlightPossibleMoves();
                        isMoving = true;
                    }
                }
            }
        }
        else if(_GameMode == GameMode.VsNetWorkHuman && Game_started == true) {
            if(isMoving) {
                Point CurPos = new Point(cell._PositionX, cell._PositionY);
                //Neu diem duoc click nam trong cac diem co the di
                if(arrMove.contains(CurPos)) {
                    //Di chuyen con co
                    cell.setImage(this._PickedCell.getImage()); //di chuyen con co den vtri do
                    _PickedCell.setIcon(null); //xoa con co o vi tri cu
                    _PickedCell.validate();

                    cell._Side = _PickedCell._Side;
                    cell._Type = _PickedCell._Type;

                    _PickedCell._Side = null;
                    _PickedCell._Type = null;

                    Point oldPos = new Point(_PickedCell._PositionX, _PickedCell._PositionY);
                    //Xoa trang thai cu, cap nhat trang thai moi
                    this._BoardState[CurPos.x][CurPos.y] = this._BoardState[oldPos.x][oldPos.y];
                    this._BoardState[oldPos.x][oldPos.y] = 0;
                    
                    Box = String.format("%d%d%d%d", oldPos.x,oldPos.y,CurPos.x,CurPos.y);
                    Send_move();
                    //Doi luot di
                    this.WhiteToMove = !this.WhiteToMove;
                }
                this.UnHighlightMoves();
                isMoving = false;
            }
            else {
                this.arrMove = new ArrayList();
                //Hiển thị các nước có thể đi
                if ((this.WhiteToMove == true && cell._Side == ChessSide.White && Iam_Server) || (this.WhiteToMove == false && cell._Side == ChessSide.Black && Iam_Client))
                {
                    int[][] BoardState = this._BoardState;//Lấy trạng thái bàn cờ
                    Point CurPos = new Point(cell._PositionX, cell._PositionY);
                    this.arrMove = ChessEngine.FindAllLegalMove(BoardState, CurPos, cell._Type);               
                    this._PickedCell = cell;
                    this.HighlightPossibleMoves();
                    isMoving = true;
                }
            }
        }
        if(checkWinner()) {
            return;
        }
        
    }

    @Override
    public void mouseEntered(MouseEvent e) {
        
        //System.out.print("board4");
    }

    @Override
    public void mouseExited(MouseEvent e) {
        
        //System.out.print("board5");
    }
    
    public void Send_move() {
        out.print(Box);
        out.print("\r\n");
        out.flush();
        
    }
        
    class Recv_Thread extends Thread {
        @Override
        public synchronized  void run  () {
            while(true) {
                
                try {
                    
                    Box=in.readLine();
                    
                } catch (IOException ex) {
                    ex.printStackTrace();
                    System.out.println("ERROR");
                }
                
                
                
                if(Box!=null) {
                    int oldPosX=Integer.parseInt(String.valueOf(Box.charAt(0)));
                    int oldPosY=Integer.parseInt(String.valueOf(Box.charAt(1)));
                    int newX=Integer.parseInt(String.valueOf(Box.charAt(2)));
                    int newY=Integer.parseInt(String.valueOf(Box.charAt(3)));
                    
                    Point oldPos = new Point(oldPosX,oldPosY);
                    Point newPos = new Point(newX,newY);
                    /***
                     * Operation to Get
                     *1- The # of Pice
                     *2- The Location X
                     *3- The Location Y
                     *
                     **/
                    
                    ChessCell ramdomCell = getCellFromLocation(oldPos);
                    ChessCell desCell = getCellFromLocation(newPos);
                    desCell.setImage(ramdomCell.getImage()); //di chuyen con co den vtri do
                    ramdomCell.setIcon(null); //xoa con co o vi tri cu
                    ramdomCell.validate();

                    desCell._Side = ramdomCell._Side;
                    desCell._Type = ramdomCell._Type;

                    ramdomCell._Side = null;
                    ramdomCell._Type = null;

                    //Xoa trang thai cu, cap nhat trang thai moi
                    _BoardState[newPos.x][newPos.y] = _BoardState[oldPos.x][oldPos.y];
                    _BoardState[oldPos.x][oldPos.y] = 0;
                    
                    if(desCell.Promotion(desCell._Side)) {
                        if(desCell._Side != ChessSide.White)
                            _BoardState[newPos.x][newPos.y] = 52;
                        else
                            _BoardState[newPos.x][newPos.y] = 51;

                        JOptionPane.showMessageDialog(null, "Promoted", "Update",JOptionPane.INFORMATION_MESSAGE);
                    }
                    //Doi luot di
                    WhiteToMove = !WhiteToMove;
                    
                }
                
                
            }
        }
    }
    
}

