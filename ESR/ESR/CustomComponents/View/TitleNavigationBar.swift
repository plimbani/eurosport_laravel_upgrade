//
//  TitleNavigationBar.swift
//  ESR
//
//  Created by Pratik Patel on 14/08/18.
//

import UIKit

protocol TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed()
}

class TitleNavigationBar: UIView {
    
    @IBOutlet var baseView: UIView!
    @IBOutlet var btnBack: UIButton!
    @IBOutlet var lblTitle: UILabel!
    @IBOutlet var containerView: UIView!
    
    var delegate: TitleNavigationBarDelegate?

    @IBOutlet var widthConstraintBackBtn: NSLayoutConstraint!
    
    required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        Bundle.main.loadNibNamed("TitleNavigationBar", owner: self, options: nil)
        
        self.baseView.frame = bounds
        self.baseView.autoresizingMask = [
            UIViewAutoresizing.flexibleWidth,
            UIViewAutoresizing.flexibleHeight
        ]
        self.addSubview(self.baseView)
        
        lblTitle.text = NULL_STRING
        lblTitle.textColor = .white
    }
    
    func hideBackButton(){
        btnBack.isHidden = true
        widthConstraintBackBtn.constant = 0
        updateConstraints()
    }
    
    func setBackgroundColor(){
        containerView.backgroundColor = UIColor.AppColor()
    }
    
    @IBAction func backBtnPressed(_ sender: UIButton) {
        delegate?.titleNavBarBackBtnPressed()
    }
}
