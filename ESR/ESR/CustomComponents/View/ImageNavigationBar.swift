//
//  ImageNavigationBar.swift
//  ESR
//
//  Created by Pratik Patel on 16/08/18.
//

import UIKit

class ImageNavigationBar: UIView {

    @IBOutlet var baseView: UIView!
    
    required init?(coder aDecoder: NSCoder) {
        super.init(coder: aDecoder)
        Bundle.main.loadNibNamed("ImageNavigationBar", owner: self, options: nil)
        
        self.baseView.frame = bounds
        self.baseView.autoresizingMask = [
            UIView.AutoresizingMask.flexibleWidth,
            UIView.AutoresizingMask.flexibleHeight
        ]
        self.addSubview(self.baseView)
    }
}
