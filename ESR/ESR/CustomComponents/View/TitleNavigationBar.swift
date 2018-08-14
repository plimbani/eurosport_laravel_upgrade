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
    
    var delegate: TitleNavigationBarDelegate?

    required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        Bundle.main.loadNibNamed("TitleNavigationBar", owner: self, options: nil)
        
        self.baseView.frame = bounds
        self.baseView.autoresizingMask = [
            UIViewAutoresizing.flexibleWidth,
            UIViewAutoresizing.flexibleHeight
        ]
        self.addSubview(self.baseView)
    }
    
    @IBAction func backBtnPressed(_ sender: UIButton) {
        delegate?.titleNavBarBackBtnPressed()
    }
}
