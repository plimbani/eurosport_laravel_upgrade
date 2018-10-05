//
//  ClubCategoryVC.swift
//  ESR
//
//  Created by Pratik Patel on 10/09/18.
//

import UIKit

class ClubCategoryVC: SuperViewController {

    @IBOutlet var txtSearch: UITextField!
    @IBOutlet var table: UITableView!
    
    var ageCategoriesList = NSArray()
    var heightAgeCategoryCell: CGFloat = 0
    
    override func viewDidLoad() {
        super.viewDidLoad()
        initialize()
    }
    
    override func viewWillAppear(_ animated: Bool) {
        sendAgeCategoriesRequest()
    }
    
    func initialize() {
        txtSearch.placeholder = String.localize(key: "placeholder_search_tab_category")
        txtSearch.setLeftPaddingPoints(35)
        txtSearch.delegate = self
        txtSearch.returnKeyType = .done
        txtSearch.layer.cornerRadius = (txtSearch.frame.size.height / 2)
        txtSearch.clipsToBounds = true
        txtSearch.font = UIFont(name: Font.HELVETICA_REGULAR, size: Font.Size.commonTextFieldTxt)
        
        // Height for cell
        _ = cellOwner.loadMyNibFile(nibName: kNiB.Cell.AgeCategoryCell)
        heightAgeCategoryCell = (cellOwner.cell as! AgeCategoryCell).getCellHeight()
    }
    
    func sendAgeCategoriesRequest() {
        if APPDELEGATE.reachability.connection == .none {
            return
        }
        
        self.view.showProgressHUD()
        var parameters: [String: Any] = [:]
        if let userData = ApplicationData.sharedInstance().getUserData() {
            parameters["tournament_id"] = userData.tournamentId
        }
        
        ApiManager().getAgeCategories(parameters, success: { (result) in
            DispatchQueue.main.async {
                self.view.hideProgressHUD()
                
                if let data = result.value(forKey: "data") as? NSArray {
                    self.ageCategoriesList = data
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

extension ClubCategoryVC: UITextFieldDelegate {
    func textFieldDidBeginEditing(_ textField: UITextField) {
        
    }
}

extension ClubCategoryVC: UITableViewDataSource, UITableViewDelegate {
    
    func tableView(_ tableView: UITableView, numberOfRowsInSection section: Int) -> Int {
        return ageCategoriesList.count
    }
    
    func tableView(_ tableView: UITableView, heightForRowAt indexPath: IndexPath) -> CGFloat {
        return heightAgeCategoryCell
    }
    
    func tableView(_ tableView: UITableView, cellForRowAt indexPath: IndexPath) -> UITableViewCell {
        var cell = tableView.dequeueReusableCell(withIdentifier: "AgeCategoryCell") as? AgeCategoryCell
        if cell == nil {
            _ = cellOwner.loadMyNibFile(nibName: "AgeCategoryCell")
            cell = cellOwner.cell as? AgeCategoryCell
        }
        cell?.record = ageCategoriesList[indexPath.row] as! NSDictionary
        cell?.reloadCell()
        return cell!
    }
    
    func tableView(_ tableView: UITableView, didSelectRowAt indexPath: IndexPath) {
        let viewController = Storyboards.AgeCategories.instantiateAgeCategoriesGroupsSummaryVC()
        viewController.dicGroup = (ageCategoriesList[indexPath.row] as! NSDictionary)
        self.navigationController?.pushViewController(viewController, animated: true)
    }
}
