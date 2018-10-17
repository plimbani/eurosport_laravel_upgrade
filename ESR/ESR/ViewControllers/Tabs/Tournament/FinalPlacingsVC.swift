//
//  FinalPlacingsVC.swift
//  ESR
//
//  Created by Pratik Patel on 15/10/18.
//

import UIKit

class FinalPlacingsVC: SuperViewController {

    var finalPlacingsList = NSArray()
    var heightFinalPlacingsCell: CGFloat = 0
    var ageCategoryId: Int = NULL_ID
    
    @IBOutlet var table: UITableView!
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    func initialize(){
        titleNavigationBar.lblTitle.text = String.localize(key: "title_final_placing")
        titleNavigationBar.delegate = self
        titleNavigationBar.setBackgroundColor()
        // Checks internet connectivity
        setConstraintLblNoInternet(APPDELEGATE.reachability.connection == .none)
        
        // To show/hide internet view in Navigation bar
        NotificationCenter.default.addObserver(self, selector: #selector(showHideNoInternetView(_:)), name: .internetConnectivity, object: nil)
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.FinalPlacingsCell)
        heightFinalPlacingsCell = (cellOwner.cell as! FinalPlacingsCell).getCellHeight()
        
        sendFinalPlacingsRequest()
    }
    
    deinit {
        NotificationCenter.default.removeObserver(self, name: .internetConnectivity, object: nil)
    }
    
    @objc func showHideNoInternetView(_ notification: NSNotification) {
        if notification.userInfo != nil {
            if let isShow = notification.userInfo![kNotification.isShow] as? Bool {
                setConstraintLblNoInternet(isShow)
            }
        }
    }
    
    func sendFinalPlacingsRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        if let userData = ApplicationData.sharedInstance().getUserData() {
            parameters["tournamentId"] = userData.tournamentId
        }
        
        parameters["ageCategoryId"] = ageCategoryId
        
        ApiManager().getFinalPlacings(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    self.finalPlacingsList = data
                }
                
                self.table.reloadData()
            }
        }) { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
            }
        }
    }
}

extension FinalPlacingsVC: TitleNavigationBarDelegate {
    func titleNavBarBackBtnPressed() {
        self.navigationController?.popViewController(animated: true)
    }
}

extension FinalPlacingsVC: UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return finalPlacingsList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return heightFinalPlacingsCell
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = tableView.dequeueReusableCell(withIdentifier: "FinalPlacingsCell") as? FinalPlacingsCell
        if cell == nil {
            _ = cellOwner.loadMyNibFile(nibName: "FinalPlacingsCell")
            cell = cellOwner.cell as? FinalPlacingsCell
        }
        cell!.record = finalPlacingsList[indexPath.row] as! NSDictionary
        cell!.reloadCell()
        return cell!
    }
}
