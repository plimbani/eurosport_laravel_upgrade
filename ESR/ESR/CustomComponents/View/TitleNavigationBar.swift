//
//  TitleNavigationBar.swift
//  ESR
//
//  Created by Pratik Patel on 14/08/18.
//

import UIKit

@objc protocol TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed()
    @objc optional func titleNavBarBtnFinalPlacingsPressed()
}

class TitleNavigationBar: UIView {
    
    @IBOutlet var baseView: UIView!
    @IBOutlet var btnBack: UIButton!
    @IBOutlet var lblTitle: UILabel!
    @IBOutlet var containerView: UIView!
    @IBOutlet var btnFinalPlacings: UIButton!
    var delegate: TitleNavigationBarDelegate?
    
    @IBOutlet var widthConstraintBackBtn: NSLayoutConstraint!
    @IBOutlet var widthConstraintBtnFinalPlacings: NSLayoutConstraint!
    
    let btnFinalPlacingsAttributes : [NSAttributedString.Key: Any] = [
        NSAttributedString.Key.font : UIFont.init(name: Font.HELVETICA_MEDIUM, size: 18.0),
        NSAttributedString.Key.foregroundColor : UIColor.white,
        NSAttributedString.Key.underlineStyle : NSUnderlineStyle.single.rawValue]
    
    var isFinalPlacings: Bool = false {
        didSet {
            btnFinalPlacings.isHidden = !isFinalPlacings
        }
    }
    
    override func awakeFromNib() {
        isFinalPlacings = false
    }
    
    required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        Bundle.main.loadNibNamed("TitleNavigationBar", owner: self, options: nil)
        
        self.baseView.frame = bounds
        self.baseView.autoresizingMask = [
            UIView.AutoresizingMask.flexibleWidth,
            UIView.AutoresizingMask.flexibleHeight
        ]
        self.addSubview(self.baseView)
        
        lblTitle.text = NULL_STRING
        lblTitle.textColor = .white
        
        btnFinalPlacings.setAttributedTitle(NSMutableAttributedString(string: "FINAL PLACINGS",
                                                                     attributes: btnFinalPlacingsAttributes), for: .normal)
    }
    
    func hideBackButton(){
        btnBack.isHidden = true
        widthConstraintBackBtn.constant = 0
        updateConstraints()
    }
    
    func setBackgroundColor(){
        containerView.backgroundColor = UIColor.AppColor()
    }
    
    @IBAction func btnFinalPlacingsPressed(_ sender: UIButton) {
        delegate?.titleNavBarBtnFinalPlacingsPressed?()
    }
    
    @IBAction func backBtnPressed(_ sender: UIButton) {
        delegate?.titleNavBarBackBtnPressed()
    }
}
