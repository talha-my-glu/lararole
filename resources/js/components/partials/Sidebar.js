import React, { useState } from 'react'
import { Link } from 'react-router-dom'
import { Layout, Menu } from 'antd'
import { HomeOutlined, PartitionOutlined, UsergroupAddOutlined } from '@ant-design/icons'

const { Sider } = Layout

function Sidebar () {
  const [collapsed, setCollapsed] = useState(false)

  function onCollapse () {
    setCollapsed(!collapsed)
  }

  return (
    <Sider
      theme="light"
      collapsed={collapsed}
      onCollapse={onCollapse}
      style={{
        overflow: 'auto',
        height: '100vh',
        position: 'fixed',
        left: 0
      }}
    >
      <div className="logo"/>
      <Menu defaultSelectedKeys={[window.location.pathname.split('/')[2] || 'home']} mode="inline">
        <Menu.Item key="home">
          <Link to='/lararole' replace>
            <HomeOutlined/>
            <span>Home</span>
          </Link>
        </Menu.Item>
        <Menu.Item key="module">
          <Link to='/lararole/module'>
            <PartitionOutlined/>
            <span>Module</span>
          </Link>
        </Menu.Item>
        <Menu.Item key="role">
          <Link to='/lararole/role'>
            <UsergroupAddOutlined/>
            <span>Role</span>
          </Link>
        </Menu.Item>
      </Menu>
    </Sider>
  )
}

export default Sidebar
