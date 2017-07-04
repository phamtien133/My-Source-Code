/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package general;

import java.awt.Point;
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;
import java.util.Random;

import pieces.*;
/**
 *
 * @author VuQuang
 */

enum PieceValue
{
    Pawn(100),
    Bishop(330),
    Knight(320),
    Rook(500),
    Queen(900),
    King(10000);

    private int value;
    PieceValue(int val) {
        this.value = val;
    }

    public int getValue() {
        return value;
    }

    public void setValue(int value) {
        this.value = value;
    }

    public static PieceValue getName(int val) {
        for(PieceValue type:PieceValue.values()) {
            if(type.getValue() == val) {
                return type;
            }
        }
        return null;
    }
}

public class ChessEngine {

    static int MaxDepth = 5;
    static ChessSide Myside, OppSide;
    static Move MyBestMove = new Move();
    // static long Nodesize() = 0;
    /*
    * "Game Logic"
    */
    public static ArrayList FindAllPossibleMove(int[][] arrState, Point CurPos, ChessPieceType eType)
    {
        ArrayList arrMove = new ArrayList();

        if (eType == ChessPieceType.Pawn)
        {
            arrMove = Pawn.FindAllPossibleMove(arrState, CurPos);
        }
        if (eType == ChessPieceType.Knight)
        {
            arrMove = Knight.FindAllPossibleMove(arrState, CurPos);
        }

        if (eType == ChessPieceType.Bishop)
        {
            arrMove = Bishop.FindAllPossibleMove(arrState, CurPos);
        }

        if (eType == ChessPieceType.Rook)
        {
            arrMove = Rook.FindAllPossibleMove(arrState, CurPos);
        }

        if (eType == ChessPieceType.Queen)
        {
            arrMove = Queen.FindAllPossibleMove(arrState, CurPos);
        }

        if (eType == ChessPieceType.King)
        {
            arrMove = King.FindAllPossibleMove(arrState, CurPos);
            ChessSide eSide = ChessSide.getName(arrState[CurPos.x][CurPos.y] % 10);
            //King.addCastlingPoint(arrState, eSide, arrMove);

        }
        return arrMove;
    }

    /// <summary>
    /// Trả về độ di động của Side cần kiểm tra
    /// </summary>
    static int Mobility(ChessSide eSide, int[][] BoardState)
    {

        int intSide = 0;
        if (eSide == ChessSide.White)
        {
            intSide = 2;
        }
        else
        {
            intSide = 1;
        }
        int intMobility = 0;
        for (int y = 1; y <= 8; y++)
            for (int x = 1; x <= 8; x++)
                if (BoardState[x][y] > 0)
                {
                    int side = BoardState[x][y] % 10;
                    if (side == intSide)
                    {
                        int intType = BoardState[x][y] / 10;
                        intMobility += FindAllLegalMove(BoardState, new Point(x, y), ChessPieceType.getName(intType)).size();
                    }
                }
        return intMobility;
    }
    
    public static ArrayList FindAllLegalMove(int[][] arrState, Point CurPos, ChessPieceType eType)
    {
        ArrayList arrPossibleMove = FindAllPossibleMove(arrState, CurPos, eType);

        if (arrPossibleMove.size() == 0)
            return arrPossibleMove;

        ArrayList arrLegalMove = new ArrayList();

        //Những nước đi làm cho quân Vua phe mình bị chiếu được xem là không hợp lệ
        int[][] arrNewState = new int[10][10];
        //Array.Copy(arrState, arrNewState, arrState.length);
        arrNewState = Arrays.copyOf(arrState, arrState.length);
        ChessSide eSide = ChessSide.getName(arrState[CurPos.x][CurPos.y] % 10);
        for (Object q : arrPossibleMove)
        {
            Point p = (Point) q;
            int tmp = arrNewState[p.x][p.y];//Quân cờ tại vị trí mới
            arrNewState[p.x][p.y] = eType.getValue() * 10 + eSide.getValue();//Thay quân cờ tại vị trí mới
            arrNewState[CurPos.x][CurPos.y] = 0;//Xóa quân cờ tại vị trí cũ

            if (King.IsChecked(arrNewState, eSide) == false)
            {
                arrLegalMove.add(p);
            }
            arrNewState[CurPos.x][CurPos.y] = arrNewState[p.x][p.y];//Cho quân cờ quay lại vị trí cũ
            arrNewState[p.x][p.y] = tmp;//Trả lại quân cờ nằm ở vị trí mới                
        }
        return arrLegalMove;
    }
    
    public static Boolean CanMove(ArrayList arrLegalMove, Point NewPos)
    {

        for (Object q : arrLegalMove)//Kiểm tra vị trí cần đến có trong danh sách có thể đi hay không
        {
            Point p = (Point) q;
            if (p == NewPos)
            {
                return true;
            }
        }
        return false;
    }
    /*
    *Hàm kiểm tra 1 phe có còn Nước Đi Hợp Lệ hay không
    * 1. Duyệt tất cả các quân cờ.
    * 2. Với mỗi quân cờ tìm tất cả các bước đi hợp lệ
    * 3. Nếu tồn tại ít nhất 1 nước đi hợp lệ thì trả vè true còn lại trả về false
    */
    public static Boolean LegalMoveAvaiable(int[][] arrState, ChessSide eSide)
    {
        int intSide = eSide.getValue();
        for (int y = 1; y <= 8; y++)
            for (int x = 1; x <= 8; x++)
            {
                if (arrState[x][y] > 0 && arrState[x][y] % 10 == intSide)
                {
                    ChessPieceType eType = ChessPieceType.getName(arrState[x][y] / 10);
                    if (FindAllLegalMove(arrState, new Point(x, y), eType).size() > 0)
                        return true;
                }
            }
        return false;
    }
    
    //Kiểm tra Chiếu Bí, Hòa Cờ(chưa xét trường hợp bất biến 3 lần) 
    //Kiểm tra truòng hợp hòa cờ do cả 2 bên không đủ quân để chiếu bí đối phương
    //Kiểm tra trường hợp hòa cờ do bất biến 3 lần
    
    //Kiem tra co ket thuc game hay chua
    private static Boolean IsEndGame(int[][] BoardState, ChessSide eMySide)
    {
        int intSide = 0;
        if (eMySide == ChessSide.White)
        {
            intSide = 2;
        }
        else
        {
            intSide = 1;
        }

        int MyScore = 0;
        int OppScore = 0;

        for (int y = 1; y <= 8; y++)
            for (int x = 1; x <= 8; x++)
                if (BoardState[x][y] > 0)
                {
                    int side = BoardState[x][y] % 10;

                    if (side == intSide)
                    {
                        int intType = BoardState[x][y] / 10;

                        switch (intType)
                        {
                            case 1: MyScore += (int)PieceValue.Pawn.getValue(); break;
                            case 2: MyScore += (int)PieceValue.Bishop.Pawn.getValue(); break;
                            case 3: MyScore += (int)PieceValue.Knight.Pawn.getValue(); break;
                            case 4: MyScore += (int)PieceValue.Rook.Pawn.getValue(); break;
                            case 5: MyScore += (int)PieceValue.Queen.Pawn.getValue(); break;
                        }
                    }
                    else
                    {
                        int intType = BoardState[x][y] / 10;
                        switch (intType)
                        {
                            case 1: OppScore += (int)PieceValue.Pawn.Pawn.getValue(); break;
                            case 2: OppScore += (int)PieceValue.Bishop.Pawn.getValue(); break;
                            case 3: OppScore += (int)PieceValue.Knight.Pawn.getValue(); break;
                            case 4: OppScore += (int)PieceValue.Rook.Pawn.getValue(); break;
                            case 5: OppScore += (int)PieceValue.Queen.Pawn.getValue(); break;
                        }
                    }
                }

        return MyScore <= 1350 && OppScore <= 1350;

    }
    
    //Danh gia the co
    private static int Evaluate(int[][] BoardState)
    {
        int intSide = 0;
        ChessSide eSide = Myside;
        ChessSide s = OppSide;
        Boolean insufficientMaterial = true;
        if (eSide == ChessSide.White)
        {
            intSide = 2;
        }
        else
        {
            intSide = 1;
        }
        int value = 0;

        int MyKnightCount = 0;
        int OppKnightCount = 0;

        Boolean bEndGame = IsEndGame(BoardState, Myside);

        Boolean MyBlackBishopAvailable = false;
        Boolean MyWhiteBishopAvailable = false;
        Boolean OppBlackBishopAvailable = false;
        Boolean OppWhiteBishopAvailable = false;

        /*
        * Nếu x+y là số lẻ thì đó là ô Trắng
        * Nếu x+y là số chẵn thì đó là ô Đen
        */

        for (int y = 1; y <= 8; y++)
            for (int x = 1; x <= 8; x++)
                if (BoardState[x][y] > 0)
                {
                    int side = BoardState[x][y] % 10;

                    if (side == intSide)
                    {
                        int intType = BoardState[x][y] / 10;

                        switch (intType)
                        {
                            case 1: value += (int)PieceValue.Pawn.getValue(); value += Pawn.GetPositionValue(new Point(x, y), Myside); insufficientMaterial = false; break;
                            case 2: value += (int)PieceValue.Bishop.getValue(); if ((x + y) % 2 == 1) MyWhiteBishopAvailable = true; else MyBlackBishopAvailable = true; value += Bishop.GetPositionValue(new Point(x, y), Myside); break;
                            case 3: value += (int)PieceValue.Knight.getValue(); value += Knight.GetPositionValue(new Point(x, y), Myside); MyKnightCount++; break;
                            case 4: value += (int)PieceValue.Rook.getValue(); value += Rook.GetPositionValue(new Point(x, y), Myside); insufficientMaterial = false; break;
                            case 5: value += (int)PieceValue.Queen.getValue(); value += Queen.GetPositionValue(new Point(x, y), Myside); insufficientMaterial = false; break;
                            case 6: value += King.GetPositionValue(new Point(x, y), Myside, bEndGame); break;

                        }
                    }
                    else
                    {
                        int intType = BoardState[x][y] / 10;
                        switch (intType)
                        {
                            case 1: value -= (int)PieceValue.Pawn.getValue(); value -= Pawn.GetPositionValue(new Point(x, y), OppSide); insufficientMaterial = false; break;
                            case 2: value -= (int)PieceValue.Bishop.getValue(); if ((x + y) % 2 == 1) OppWhiteBishopAvailable = true; else OppBlackBishopAvailable = true; value -= Bishop.GetPositionValue(new Point(x, y), OppSide); break;
                            case 3: value -= (int)PieceValue.Knight.getValue(); value -= Knight.GetPositionValue(new Point(x, y), OppSide); OppKnightCount++; break;
                            case 4: value -= (int)PieceValue.Rook.getValue(); value -= Rook.GetPositionValue(new Point(x, y), OppSide); insufficientMaterial = false; break;
                            case 5: value -= (int)PieceValue.Queen.getValue(); value -= Queen.GetPositionValue(new Point(x, y), OppSide); insufficientMaterial = false; break;
                            case 6: value -= King.GetPositionValue(new Point(x, y), OppSide, bEndGame); break;

                        }
                    }
                }
        /*
         * Việc sở hữu 2 quân tượng sẽ làm tăng lợi thế cho bên sở hữu nó.
         * Bên cạnh đó quân Tượng rất hiệu quả khi EndGame(Tàn Cuộc), còn quân Mã thì ngược lại
         */

        //ván cờ không hòa do insufficientMaterial khi
        //1 trong 2 bên còn Tốt hoặc xe, hoặc Hậu
        //1 trong 2 bên còn 2 mã hoặc 2 tượng khác màu(ô)
        //1 trong 2 bên còn 1 mã và 1 tượng
        //Mỗi bên còn 1 quân tượng khác màu(ô)
        if (OppKnightCount > 1 || MyKnightCount > 1)
            insufficientMaterial = false;
        if ((MyKnightCount >= 1 && (MyBlackBishopAvailable || MyWhiteBishopAvailable)) || (OppKnightCount >= 1 && (OppBlackBishopAvailable || OppWhiteBishopAvailable)))
            insufficientMaterial = false;
        if (MyBlackBishopAvailable && MyWhiteBishopAvailable)
        {
            value += 10;
            insufficientMaterial = false;
        }
        if (OppBlackBishopAvailable && OppWhiteBishopAvailable)
        {
            value -= 10;
            insufficientMaterial = false;
        }
        if ((OppWhiteBishopAvailable && MyBlackBishopAvailable) || (OppBlackBishopAvailable && MyWhiteBishopAvailable))
            insufficientMaterial = false;

        if (insufficientMaterial == true)
            return 0;

        if (bEndGame == true)
        {
            if (MyKnightCount >= 1)
                value -= 10;
            if (OppKnightCount >= 1)
                value += 10;
            if (MyBlackBishopAvailable || MyWhiteBishopAvailable)
                value += 10;
            if (MyBlackBishopAvailable || MyWhiteBishopAvailable)
                value -= 10;
        }

        value += Mobility(eSide,BoardState);

        return value;
    }
    
    /*
    *AI:Giai thuat MinMax
    *Ham alpha-beta
    */
    public static int AlphaBeta(int[][] BoardState,ArrayList arrFEN, int depth, ChessSide eSide, int Alpha, int Beta)
    {
        //Nodesize()++;
        if (depth == 0)
        {
            return Evaluate(BoardState);
        }

        int best = -100000;
        Move bestmove = new Move();
        ArrayList arrMoves = Successors(BoardState, eSide);

        if (arrMoves.size() == 0)
        {
            //Quân Vua mình bị chiếu và người hết cờ là mình => trừ điểm mình
            if (King.IsChecked(BoardState, Myside) == true)
            {
                if (eSide == Myside)
                    return -10000 - depth;
            }
            //Quân Vua đối phươn bị chiếu và người hết cờ là đối phương => trừ điểm đối phương
            if (King.IsChecked(BoardState, OppSide) == true)
            {
                if (eSide == OppSide)
                    return -10000 - depth;
            }

            return 0;
        }
        //Sắp xếp các nước đi nhằm tăng hiệu quả của hàm Alpha Beta
        int intPromotionType = 2;
        if (depth > 1)
        {

            for (Object q : arrMoves)
            {
                Move m = (Move)q;
                int[][] State = new int[10][10];
                //Array.Copy(BoardState, State, BoardState.Length);
                State = Arrays.copyOf(BoardState, BoardState.length);
                State = BoardState;
                Point c = m.getCurPos();
                Point n = m.getNewPos();

                int value = State[c.x][c.y];

                if (value / 10 == 1)
                {
                    if (value % 10 == 1 && n.y == 1)
                    {
                        value = intPromotionType * 10 + 1;
                        intPromotionType++;
                        State[c.x][c.y] = value;
                    }
                    if (value % 10 == 2 && n.y == 8)
                    {
                        value = intPromotionType * 10 + 2;
                        intPromotionType++;
                        State[c.x][c.y] = value;
                    }
                }
                if (intPromotionType == 6)
                    intPromotionType = 2;
                TryMove(State, m);
                int Score = AlphaBeta(State,arrFEN , 0, eSide, -Beta, -Alpha);
                m.setScore(Score);
            }
            Sort(arrMoves, eSide);
        }
        intPromotionType = 5;


        while (arrMoves.size() > 0 && best < Beta)
        {

            int[][] State = new int[10][10];
            //Array.Copy(BoardState, State, BoardState.Length);
            State = Arrays.copyOf(BoardState, BoardState.length);

            Move Move = (Move)arrMoves.get(arrMoves.size() - 1);

            Point c = Move.getCurPos();
            Point n = Move.getNewPos();

            int x = State[c.x][c.y];

            if (x / 10 == 1)
            {
                if (x % 10 == 1 && n.y == 1)
                {
                    x = intPromotionType * 10 + 1;
                    intPromotionType--;
                    State[c.x][c.y] = x;
                }
                if (x % 10 == 2 && n.y == 8)
                {
                    x = intPromotionType * 10 + 2;
                    intPromotionType--;
                    State[c.x][c.y] = x;
                }
                if (intPromotionType == 1)
                    intPromotionType = 5;
            }

            TryMove(State, Move);
            arrMoves.remove(arrMoves.size() - 1);
            //Đã đi thử xong
            ArrayList arrFENNew = new ArrayList();
            arrFENNew.addAll(arrFEN);

            ChessSide s;
            if (eSide == ChessSide.White)
                s = ChessSide.Black;
            else
                s = ChessSide.White;

            if (best > Alpha)
                Alpha = best;

            int value = -AlphaBeta(State,arrFENNew , depth - 1, s, -Beta, -Alpha);

            if (value > best)
            {
                best = value;
                bestmove = Move;
            }
        }

        if (depth == MaxDepth)
        {
            bestmove.setScore(best);
            MyBestMove = bestmove;
        }

        return best;
    }
    
    // "Hàm Tạo Trạng Thái Bàn Cờ Từ 1 Nước Đi Hợp Lệ"
    static void TryMove(int[][] State, Move Move)
    {

        Point c = Move.getCurPos();
        Point n = Move.getNewPos();

        int value = State[c.x][c.y];
        //Nhập Thành
        if (value / 10 == 6)
        {
            if (Math.abs(n.x - c.y) == 2)
            {
                if (n.x == 7)
                {
                    int tmp = State[8][n.y];
                    State[8][n.y] = 0;
                    State[6][n.y] = tmp;
                }
                if (n.x == 3)
                {
                    int tmp = State[1][n.y];
                    State[1][n.y] = 0;
                    State[4][n.y] = tmp;
                }
            }
        }


        State[n.x][n.y] = value;
        State[c.x][c.y] = 0;

    }
    
    // "Successors(Hàm Tìm Tất Cả Các Nước Đi Hợp Lệ)"
    private static ArrayList Successors(int[][] BoardState, ChessSide eSide)
    {

        int intSide = 0;
        if (eSide == ChessSide.White)
        {
            intSide = 2;
        }
        else
        {
            intSide = 1;
        }
        ArrayList arrMoves = new ArrayList();
        for (int y = 1; y <= 8; y++)
            for (int x = 1; x <= 8; x++)
                if (BoardState[x][ y] > 0)
                {
                    int side = BoardState[x][y] % 10;
                    if (side == intSide)
                    {
                        int intType = BoardState[x][y] / 10;
                        ArrayList arr = FindAllLegalMove(BoardState, new Point(x, y), ChessPieceType.getName(intType));
                        for (Object q : arr)
                        {
                            Point p = (Point)q;
                            Move Move = new Move(new Point(x, y), p);
                            arrMoves.add(Move);
                        }
                    }
                }
        return arrMoves;
    }

    //sắp xếp các nước đi để tăng hiệu suất hàm alpha beta
    private static void Sort(ArrayList arrMoves, ChessSide eSide)
    {
        int[] a = new int[arrMoves.size()];
        int[] b = new int[arrMoves.size()];

        for (int i = 0; i < arrMoves.size(); i++)
        {
            Move m = (Move)arrMoves.get(i);
            a[i] = m.getScore();
            b[i] = i;
        }

        QuickSort(a, b, 0, arrMoves.size() - 1);


        ArrayList arr = new ArrayList();
        //arr.AddRange(arrMoves);
        arr.addAll(arrMoves);
        if (eSide == OppSide) {
            //Array.Reverse(b);
            Collections.reverse(arr);
        }
        for (int i = 0; i < arr.size(); i++)
        {
            //arrMoves[i] = arr[b[i]];
            arrMoves.set(i, arr.get(b[i]));
        }

    }
    
    //Test thử quick sort 
    private static void QuickSort(int[] a, int[] b, int l, int r)
    {
        int i, j;
        int x;
        x = a[(l + r) / 2];
        i = l;
        j = r;
        do
        {
            while (a[i] < x)
                i++;
            while (a[j] > x)
                j--;
            if (i <= j)
            {
                int tmp = a[i];
                a[i] = a[j];
                a[j] = tmp;

                tmp = b[i];
                b[i] = b[j];
                b[j] = tmp;
                i++;
                j--;
            }
        }
        while (i < j);
        if (l < j)
            QuickSort(a, b, l, j);
        if (i < r)
            QuickSort(a, b, i, r);

    }
    
    // "Hàm Tạo Nước Đi Hợp Lệ Ngẫu Nhiên"
    public static Move RandomMove(int[][] BoardState, ChessSide eSide)
    {
        ArrayList arrMoves = Successors(BoardState, eSide);
        if (arrMoves.size() == 0)
            return null;
        Random rd = new Random(arrMoves.size());
        int value = rd.nextInt(arrMoves.size());
        return (Move)arrMoves.get(value);
    }
    
    //Nuoc di su dung AlphaBeta
    public static Move GenerateMove(int[][] BoardState, ChessSide eSide, int difficult)
    {
        ArrayList arrFEN = new ArrayList();
        //ArrayList arrMove = new ArrayList();
        //NodeCount = 0;         
        MaxDepth = difficult;
        Move Move = new Move();
        MyBestMove = new Move();
        if (MaxDepth > 0)
        {
            Myside = eSide;
            if (Myside == ChessSide.Black)
                OppSide = ChessSide.White;
            else
                OppSide = ChessSide.Black;


            int alpha = -50000;
            int beta = 50000;

            if (Mobility(Myside, BoardState) + Mobility(OppSide, BoardState) < 30)
                MaxDepth = 6;

            AlphaBeta(BoardState,arrFEN , MaxDepth, eSide, -beta, -alpha);

            Move = MyBestMove;

            if (Move == null)
            {
                Move = RandomMove(BoardState, eSide);
            }
        }
        else
        {
            Move = RandomMove(BoardState, eSide);
        }
        //ChessPieceType eType = ChessPieceType.getName(BoardState[Move.getCurPos().x][Move.getCurPos().y] / 10);
        //arrMove = FindAllLegalMove(BoardState, Move.getCurPos(), eType);
        //MessageBox.Show(NodeCount.ToString ());
        return Move;

    }
}
