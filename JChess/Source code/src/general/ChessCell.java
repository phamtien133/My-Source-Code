/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
package general;

import java.awt.Color;
import java.awt.Graphics;
import java.awt.Graphics2D;
import java.awt.event.MouseEvent;
import java.awt.event.MouseListener;
import java.awt.geom.Rectangle2D;
import java.awt.image.BufferedImage;
import java.util.ArrayList;
import javax.swing.ImageIcon;
import javax.swing.JLabel;
import javax.swing.JPanel;

/**
 *
 * @author VuQuang
 */
//Gop 2 class cell&piece lam 1
public class ChessCell extends JLabel{
    int _PositionX, _PositionY;//Xem quân cờ đang nằm ở ô nào
    int _CellSize; //Chiều dài cạnh của quân cờ
    ChessPieceType _Type; // Loại quân cờ (Tốt, Tượng, Mã, Xe, Hậu, Vua)
    ChessSide _Side;//Con co la quan Black hay White
    CellColor _Color;
    BufferedImage _image;//Hình ảnh của quân cờ
    public ChessCell(int PositionX, int PositionY,CellColor Color, int iCellSize)
    {
        this.setOpaque(true);
        this._PositionX = PositionX;
        this._PositionY = PositionY;
        this._CellSize = iCellSize;
        this._Color = Color;
        this.setSize(this._CellSize, this._CellSize);
        this.setBackImage(Color);

    }
    
    public ChessCell(int PositionX, int PositionY,CellColor Color, ChessSide Side,ChessPieceType Type, int iCellSize)
    {
        this.setOpaque(true);
        this._PositionX = PositionX;
        this._PositionY = PositionY;
        this._Side = Side;
        this._Type = Type;
        this._CellSize = iCellSize;
        this._Color = Color;
        this.setSize(this._CellSize, this._CellSize);
        
        //this._BackImage = ImageProcess.GetChessBoardBitMap(Side);
        this.setBackImage(Color);
        //this._image = ImageProcess.GetChessPieceBitMap(Side, Type);
        this.setImage(ImageProcess.GetChessPieceBitMap(Side, Type));

    }

    public ChessCell()
    {
        //InitializeComponent();
        //super();
        this.setOpaque(true);
    }

    public int getPositionX() {
        return _PositionX;
    }

    public void setPositionX(int _PositionX) {
        this._PositionX = _PositionX;
    }

    public int getPositionY() {
        return _PositionY;
    }

    public void setPositionY(int _PositionY) {
        this._PositionY = _PositionY;
    }
    
    public void setBackImage(CellColor color) {
        switch(color) {
            case Black:
            {
                this.setBackground(Color.BLACK);
            }
            break;
            case White:
            {
                this.setBackground(Color.WHITE);
            }
            break;
            case Blue:
            {
                this.setBackground(Color.BLUE);
            }
        }
    }
    
    public Color getBackImage() {
        switch(this._Color) {
            case Black:
            {
                return Color.BLACK;
            }
            case White:
            {
                return Color.WHITE;
            }
            case Blue:
            {
                return Color.BLUE;
            }
            default:
                return null;
        }
    }

    public int getCellSize() {
        return _CellSize;
    }

    public void setCellSize(int _CellSize) {
        this._CellSize = _CellSize;
    }

    public int getPieceSize() {
        return this._CellSize;
    }

    public void setPieceSize(int _CellSize) {
        this._CellSize = _CellSize;
    }

    public ChessPieceType getType() {
        return _Type;
    }

    public void setType(ChessPieceType _Type) {
        this._Type = _Type;
    }

    public ChessSide getSide() {
        return _Side;
    }

    public void setSide(ChessSide _Side) {
        this._Side = _Side;
    }

    public BufferedImage getImage() {
        return _image;
    }

    public void setImage(BufferedImage _image) {
        this._image = _image;
        addImage();
    }

    public CellColor getColor() {
        return _Color;
    }

    public void setColor(CellColor _Color) {
        this._Color = _Color;
    }
    
//    @Override
//    public void paintComponent(Graphics g){
//        super.paintComponent(g);
//        g.drawImage(_image, 0, 0, this._CellSize, this._CellSize, this);
//    }
    private void addImage() {
        this.setIcon(new ImageIcon(_image));
        this.setHorizontalAlignment(CENTER);
    }     
    
    public void HighlightPossibleMove()
    {
        setBackImage(CellColor.Blue);
    }
    public void UnHighlightMove()
    {
        setBackImage(this._Color);
        
    }
    
    

    //thang hau
    public Boolean Promotion(ChessSide side)
    {
        if(this._Type == ChessPieceType.Pawn) {
            if ((this._PositionX == 8) || (this._PositionY == 1))
            {
                this._Type = ChessPieceType.Queen;
                this.setImage(ImageProcess.GetChessPieceBitMap(side, ChessPieceType.Queen));
                return true;
            }
        }
        return false;
    }
}
