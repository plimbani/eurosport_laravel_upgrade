//
//  TableCellOwner.swift
//  ESR
//
//  Created by Pratik Patel on 10/08/18.
//

import UIKit

class TableCellOwner: NSObject {

    @IBOutlet var cell: UITableViewCell!
    @IBOutlet var view: UIView!
    
    func loadMyNibFile(nibName: String) -> Bool {
        // The myNib file must be in the bundle that defines self's class.
        if Bundle.main.loadNibNamed(nibName, owner: self, options: nil) == nil {
            print("Warning! Could not load \(nibName) file.\n")
            return false
        }
        return true
    }
}
