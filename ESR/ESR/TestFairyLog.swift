//
//  TestFairyLog.swift
//  ESR
//
//  Created by Pratik Patel on 14/06/19.
//

import Foundation

public func print(_ format: String, _ args: CVarArg...) {
    let message = String(format: format, arguments:args)
    print(message);
    TFLogv(message, getVaList([]))
}
